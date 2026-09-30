<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\AccountImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AccountImage>
 */
class AccountImageFactory extends Factory
{
    protected $model = AccountImage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_id' => Account::factory(),
            'path' => 'accounts/'.fake()->uuid().'.jpg',
            'is_cover' => false,
        ];
    }

    public function cover(): static
    {
        return $this->state(fn (array $attributes) => ['is_cover' => true]);
    }

    public function plain(): static
    {
        return $this->state(fn (array $attributes) => ['is_cover' => false]);
    }
}
