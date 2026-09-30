<?php

namespace Database\Factories;

use App\Enums\AccountStatus;
use App\Models\Account;
use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Account>
 */
class AccountFactory extends Factory
{
    protected $model = Account::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'account_code' => 'TEST-'.fake()->unique()->numerify('####'),
            'game_id' => Game::factory(),
            'title' => Str::title(fake()->words(3, true)),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(25_000, 2_500_000),
            'status' => AccountStatus::Available->value,
        ];
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AccountStatus::Available->value,
        ]);
    }

    public function reserved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AccountStatus::Reserved->value,
        ]);
    }

    public function sold(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => AccountStatus::Sold->value,
        ]);
    }
}
