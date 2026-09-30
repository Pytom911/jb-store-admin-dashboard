<?php

namespace Database\Seeders;

use App\Enums\AccountStatus;
use App\Models\Account;
use App\Models\Game;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
{
    public function run(): void
    {
        $games = Game::query()->pluck('id', 'slug');

        $accounts = [
            [
                'account_code' => 'ML-001',
                'game' => 'mobile-legends',
                'title' => '[DUMMY] Akun Sultan Rank Mythic',
                'description' => 'Data contoh untuk testing. Akun Sultan dengan 120 skin, 5 stiker, dan 30 diamond.',
                'price' => 150000,
                'status' => AccountStatus::Available->value,
            ],
            [
                'account_code' => 'ML-002',
                'game' => 'mobile-legends',
                'title' => '[DUMMY] Akun Epic Starlight',
                'description' => 'Data contoh untuk testing. Akun Epic dengan 40 skin dan starlight member aktif.',
                'price' => 85000,
                'status' => AccountStatus::Available->value,
            ],
            [
                'account_code' => 'ML-003',
                'game' => 'mobile-legends',
                'title' => '[DUMMY] Akun Bang Submit 5x',
                'description' => 'Data contoh untuk testing. Akun basic yang cocok untuk pemain pemula.',
                'price' => 35000,
                'status' => AccountStatus::Sold->value,
            ],
            [
                'account_code' => 'FF-001',
                'game' => 'free-fire',
                'title' => '[DUMMY] Akun Heroic Alok Bundle',
                'description' => 'Data contoh untuk testing. Akun Heroic dengan bundleluck royale dan 60 diamond.',
                'price' => 120000,
                'status' => AccountStatus::Available->value,
            ],
            [
                'account_code' => 'FF-002',
                'game' => 'free-fire',
                'title' => '[DUMMY] Akun Krono Evo MAX',
                'description' => 'Data contoh untuk testing. Akun level 70 dengan 25 bundle dan badge langka.',
                'price' => 95000,
                'status' => AccountStatus::Reserved->value,
            ],
            [
                'account_code' => 'VAL-001',
                'game' => 'valorant',
                'title' => '[DUMMY] Akun Immortal 3x Vandal',
                'description' => 'Data contoh untuk testing. Akun Immortal dengan 9 skin Vandal dan 2 knife.',
                'price' => 450000,
                'status' => AccountStatus::Available->value,
            ],
            [
                'account_code' => 'VAL-002',
                'game' => 'valorant',
                'title' => '[DUMMY] Akun Ascendant Skin Bundle',
                'description' => 'Data contoh untuk testing. Akun Ascendant dengan bundle skin season radiant.',
                'price' => 275000,
                'status' => AccountStatus::Available->value,
            ],
        ];

        foreach ($accounts as $account) {
            Account::query()->updateOrCreate(
                ['account_code' => $account['account_code']],
                [
                    'game_id' => $games[$account['game']],
                    'title' => $account['title'],
                    'description' => $account['description'],
                    'price' => $account['price'],
                    'status' => $account['status'],
                ],
            );
        }
    }
}
