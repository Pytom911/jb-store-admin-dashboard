<?php

namespace App\Models;

use App\Enums\AccountStatus;
use App\Enums\GameStatus;
use Database\Factories\GameFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

#[Fillable(['name', 'slug', 'image', 'description', 'status'])]
class Game extends Model
{
    /** @use HasFactory<GameFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => GameStatus::class,
        ];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function availableAccounts(): HasMany
    {
        return $this->accounts()->where('status', AccountStatus::Available->value);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', GameStatus::Active->value);
    }

    /**
     * Attaches the total and available account counts in one extra query,
     * which is what the public game pages need to avoid N+1.
     */
    public function scopeWithStockCounts(Builder $query): Builder
    {
        return $query->withCount(self::stockCounts());
    }

    /**
     * @return array<string, mixed>
     */
    public static function stockCounts(): array
    {
        return [
            'accounts',
            'accounts as available_accounts_count' => fn (Builder $accounts) => $accounts
                ->where('status', AccountStatus::Available->value),
        ];
    }

    protected function imageUrl(): Attribute
    {
        return Attribute::get(
            fn (mixed $value, array $attributes) => isset($attributes['image'])
                ? Storage::disk('public')->url($attributes['image'])
                : null,
        );
    }
}
