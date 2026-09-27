<?php

namespace App\Models;

use App\Enums\AccountStatus;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Number;

#[Fillable(['account_code', 'game_id', 'title', 'username', 'password', 'description', 'price', 'status'])]
#[Hidden(['username', 'password'])]
class Account extends Model
{
    /** @use HasFactory<AccountFactory> */
    use HasFactory;

    /**
     * The only columns public queries are allowed to load. Username and password
     * are deliberately absent so customer-facing pages cannot render them by accident.
     */
    public const PUBLIC_COLUMNS = [
        'id',
        'account_code',
        'game_id',
        'title',
        'description',
        'price',
        'status',
        'created_at',
        'updated_at',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'password' => 'encrypted',
            'status' => AccountStatus::class,
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function scopeWithoutCredentials(Builder $query): Builder
    {
        return $query->select(self::PUBLIC_COLUMNS);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', AccountStatus::Available->value);
    }

    public function scopeReserved(Builder $query): Builder
    {
        return $query->where('status', AccountStatus::Reserved->value);
    }

    public function scopeSold(Builder $query): Builder
    {
        return $query->where('status', AccountStatus::Sold->value);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (blank($term)) {
            return $query;
        }

        $like = '%'.str_replace(['%', '_'], ['\%', '\_'], $term).'%';

        return $query->where(function (Builder $query) use ($like) {
            $query->where('account_code', 'like', $like)
                ->orWhere('title', 'like', $like);
        });
    }

    public function scopeSorted(Builder $query, ?string $sort): Builder
    {
        return match ($sort) {
            'price_asc' => $query->orderBy('price'),
            'price_desc' => $query->orderByDesc('price'),
            default => $query->latest(),
        };
    }

    public function isAvailable(): bool
    {
        return $this->status === AccountStatus::Available;
    }

    public function formattedPrice(): string
    {
        // CLDR gives IDR two fraction digits, but rupiah prices are never fractional.
        return Number::currency((float) $this->price, in: 'IDR', locale: 'id', precision: 0);
    }

    public function whatsappUrl(): string
    {
        $message = urlencode("Halo, saya tertarik dengan akun {$this->account_code}.");

        return 'https://wa.me/'.config('marketplace.whatsapp_number')."?text={$message}";
    }
}
