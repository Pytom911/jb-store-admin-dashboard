<?php

namespace App\Models;

use Database\Factories\AccountImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

#[Fillable(['account_id', 'path', 'is_cover'])]
class AccountImage extends Model
{
    /**
     * Upper bound on how many detail images one account may carry. Enforced by the
     * form requests so admins are told about the limit before the upload happens.
     */
    public const MAX_PER_ACCOUNT = 6;

    /** @use HasFactory<AccountImageFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'is_cover' => 'boolean',
        ];
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    protected function url(): Attribute
    {
        return Attribute::get(
            fn (mixed $value, array $attributes) => isset($attributes['path'])
                ? Storage::disk('public')->url($attributes['path'])
                : null,
        );
    }
}
