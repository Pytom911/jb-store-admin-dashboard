<?php

namespace Database\Seeders;

use App\Enums\GameStatus;
use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $games = [
            [
                'name' => 'Mobile Legends',
                'slug' => 'mobile-legends',
                'description' => 'Akun Mobile Legends: Bang, Epic, Starlight, sampai Sultan. Semua sudah terverifikasi dan siap immediate.',
                'status' => GameStatus::Active->value,
            ],
            [
                'name' => 'Free Fire',
                'slug' => 'free-fire',
                'description' => 'Akun Free Fire dengan level tinggi, skin lengkap, dan binding aman. Cocok untukEphor maupunakening.',
                'status' => GameStatus::Active->value,
            ],
            [
                'name' => 'Valorant',
                'slug' => 'valorant',
                'description' => 'Akun Valorant dengan skin premium dan rank tinggi. Region Indonesia, siap transfer instan.',
                'status' => GameStatus::Active->value,
            ],
        ];

        foreach ($games as $game) {
            Game::query()->updateOrCreate(['slug' => $game['slug']], $game);
        }
    }
}
