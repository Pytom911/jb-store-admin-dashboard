<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Models\Account;
use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_every_public_page_renders_for_guests(): void
    {
        $game = Game::factory()->create();
        $account = Account::factory()->for($game)->create();

        $this->get('/')->assertOk()->assertViewIs('public.home');
        $this->get('/games')->assertOk()->assertViewIs('public.games.index');
        $this->get("/games/{$game->slug}")->assertOk()->assertViewIs('public.games.show');
        $this->get('/accounts')->assertOk()->assertViewIs('public.accounts.index');
        $this->get("/accounts/{$account->account_code}")->assertOk()->assertViewIs('public.accounts.show');
    }

    public function test_public_pages_render_without_any_data(): void
    {
        $this->get('/')->assertOk();
        $this->get('/games')->assertOk();
        $this->get('/accounts')->assertOk();
    }

    public function test_inactive_games_are_not_reachable(): void
    {
        $game = Game::factory()->inactive()->create();

        $this->get("/games/{$game->slug}")->assertNotFound();
    }

    public function test_inactive_games_are_hidden_from_listings(): void
    {
        Game::factory()->inactive()->create(['name' => 'Game Nonaktif']);

        $this->get('/games')->assertOk()->assertDontSee('Game Nonaktif');
        $this->get('/')->assertOk()->assertDontSee('Game Nonaktif');
    }

    public function test_public_pages_never_leak_account_credentials(): void
    {
        $game = Game::factory()->create();
        $account = Account::factory()->for($game)->create([
            'username' => 'rahasia_username_akun',
            'password' => 'rahasia_password_akun',
        ]);

        $this->get('/')->assertDontSee('rahasia_username_akun')->assertDontSee('rahasia_password_akun');
        $this->get('/accounts')->assertDontSee('rahasia_username_akun')->assertDontSee('rahasia_password_akun');
        $this->get("/accounts/{$account->account_code}")
            ->assertDontSee('rahasia_username_akun')
            ->assertDontSee('rahasia_password_akun');
    }

    public function test_home_shows_the_counter_totals(): void
    {
        $game = Game::factory()->create();
        Account::factory()->count(3)->for($game)->create();
        Account::factory()->for($game)->sold()->create();
        Account::factory()->for($game)->reserved()->create();

        $this->get('/')
            ->assertOk()
            ->assertViewHas('stats', [
                'games' => 1,
                'accounts' => 5,
                'available' => 3,
                'sold' => 1,
            ]);
    }

    public function test_home_only_shows_active_games(): void
    {
        $active = Game::factory()->create(['name' => 'Game Aktif']);
        Game::factory()->inactive()->create(['name' => 'Game Nonaktif']);

        $this->get('/')
            ->assertOk()
            ->assertSee($active->name)
            ->assertDontSee('Game Nonaktif');
    }

    public function test_the_account_catalog_defaults_to_available_accounts(): void
    {
        $game = Game::factory()->create();

        Account::factory()->for($game)->create(['title' => 'Akun Tersedia', 'price' => 100_000]);
        Account::factory()->for($game)->sold()->create(['title' => 'Akun Terjual', 'price' => 50_000]);

        $this->get('/accounts')
            ->assertOk()
            ->assertSee('Akun Tersedia')
            ->assertDontSee('Akun Terjual');
    }

    public function test_the_account_catalog_can_be_filtered_and_sorted(): void
    {
        $game = Game::factory()->create(['name' => 'Game Alpha']);
        $otherGame = Game::factory()->create(['name' => 'Game Beta']);

        Account::factory()->for($game)->create(['title' => 'Akun Mahal', 'price' => 900_000]);
        Account::factory()->for($game)->create(['title' => 'Akun Murah', 'price' => 100_000]);
        Account::factory()->for($otherGame)->create(['title' => 'Akun Game Beta', 'price' => 50_000]);

        $this->get(route('accounts.index', ['game' => $game->slug, 'sort' => 'price_desc']))
            ->assertOk()
            ->assertSee('Akun Mahal')
            ->assertSee('Akun Murah')
            ->assertDontSee('Akun Game Beta');
    }

    public function test_the_account_catalog_searches_by_code(): void
    {
        $game = Game::factory()->create();
        $match = Account::factory()->for($game)->create(['title' => 'Akun Alpha']);
        Account::factory()->for($game)->create(['title' => 'Akun Beta']);

        $this->get(route('accounts.index', ['search' => $match->account_code]))
            ->assertOk()
            ->assertSee('Akun Alpha')
            ->assertDontSee('Akun Beta');
    }

    public function test_a_game_page_lists_its_own_accounts(): void
    {
        $game = Game::factory()->create();
        $otherGame = Game::factory()->create();

        Account::factory()->for($game)->create(['title' => 'Akun Dalam Game']);
        Account::factory()->for($otherGame)->create(['title' => 'Akun Game Lain']);

        $this->get("/games/{$game->slug}")
            ->assertOk()
            ->assertSee('Akun Dalam Game')
            ->assertDontSee('Akun Game Lain');
    }

    public function test_a_game_page_switches_status_with_a_query_parameter(): void
    {
        $game = Game::factory()->create();

        Account::factory()->for($game)->create(['title' => 'Akun Tersedia']);
        Account::factory()->for($game)->sold()->create(['title' => 'Akun Terjual']);

        $this->get("/games/{$game->slug}?status=".AccountStatus::Sold->value)
            ->assertOk()
            ->assertSee('Akun Terjual')
            ->assertDontSee('Akun Tersedia');
    }

    public function test_the_account_detail_page_404s_for_an_unknown_code(): void
    {
        $this->get('/accounts/TIDAK-ADA-0000')->assertNotFound();
    }

    public function test_the_catalog_links_to_whatsapp_with_the_configured_number(): void
    {
        config()->set('marketplace.whatsapp_number', '628999888777');

        $game = Game::factory()->create();
        Account::factory()->for($game)->create(['account_code' => 'WA-1234']);

        $this->get('/accounts')
            ->assertOk()
            ->assertSee('https://wa.me/628999888777', false)
            ->assertSee('WA-1234');
    }

    public function test_the_layout_marks_the_current_page_in_navigation(): void
    {
        $this->get(route('games.index'))->assertOk()->assertSee('aria-current="page"', false);
    }

    public function test_game_covers_render_when_an_image_is_uploaded(): void
    {
        Storage::fake('public');

        $image = UploadedFile::fake()->create('cover.jpg', 64, 'image/jpeg');
        $path = $image->store('games', 'public');

        $game = Game::factory()->create(['name' => 'Game Bergambar', 'image' => $path]);

        $this->get('/games')
            ->assertOk()
            ->assertSee($game->image_url, false);
    }

    public function test_games_without_an_image_fall_back_to_their_initial(): void
    {
        Game::factory()->create(['name' => 'Zenless Zone Zero']);

        $this->get('/games')->assertOk()->assertSee('Z');
    }
}
