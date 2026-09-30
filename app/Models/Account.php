<?php

namespace App\Models;

use App\Enums\AccountStatus;
use Database\Factories\AccountFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Number;

#[Fillable(['account_code', 'game_id', 'title', 'description', 'price', 'status'])]
class Account extends Model
{
    /** @use HasFactory<AccountFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => AccountStatus::class,
        ];
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    /**
     * Cover first, then oldest. The order is stated explicitly because the
     * (account_id, is_cover) index is enough for the database to return rows in
     * is_cover order on its own, which would file the cover last in the galleries.
     */
    public function images(): HasMany
    {
        return $this->hasMany(AccountImage::class)
            ->orderByDesc('is_cover')
            ->orderBy('id');
    }

    /**
     * The single image listings render. Deliberately not ofMany(): a plain
     * where + orderBy resolves in one query for every account an eager load
     * covers, where ofMany() would add an aggregate subquery per relation.
     */
    public function coverImage(): HasOne
    {
        return $this->hasOne(AccountImage::class)
            ->where('is_cover', true)
            ->orderBy('id');
    }

    /**
     * Attaches the detail image count in one extra query, so listing pages can
     * badge the "more images" counter without touching the relation per row.
     */
    public function scopeWithImageCount(Builder $query): Builder
    {
        return $query->withCount('images');
    }

    public function remainingImageSlots(): int
    {
        if (! $this->exists) {
            return AccountImage::MAX_PER_ACCOUNT;
        }

        return max(0, AccountImage::MAX_PER_ACCOUNT - $this->images()->count());
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
