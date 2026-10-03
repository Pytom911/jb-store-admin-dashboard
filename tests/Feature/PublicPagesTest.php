<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Models\Account;
use App\Models\Game;
use App\Models\User;
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

    public function test_public_pages_only_expose_listing_fields(): void
    {
        $game = Game::factory()->create();
        $account = Account::factory()->for($game)->create(['title' => 'Akun Sultan Mono']);

        // The credential columns no longer exist, so a leaked one could only come
        // from an attribute the model still exposes. Guard both entry points.
        foreach (['/', '/accounts', "/accounts/{$account->account_code}"] as $uri) {
            $this->get($uri)
                ->assertOk()
                ->assertDontSee('username')
                ->assertDontSee('password')
                ->assertSee('Akun Sultan Mono');
        }

        $this->assertArrayNotHasKey('username', $account->getAttributes());
        $this->assertArrayNotHasKey('password', $account->getAttributes());
    }

    public function test_account_covers_and_galleries_render_on_public_pages(): void
    {
        Storage::fake('public');

        $game = Game::factory()->create();
        $account = Account::factory()->for($game)->create();

        $cover = $account->images()->create(['path' => 'accounts/sampul.jpg', 'is_cover' => true]);
        $detail = $account->images()->create(['path' => 'accounts/detail.jpg', 'is_cover' => false]);

        $this->get('/accounts')
            ->assertOk()
            ->assertSee($cover->url, false);

        $this->get("/accounts/{$account->account_code}")
            ->assertOk()
            ->assertSee($cover->url, false)
            ->assertSee($detail->url, false);
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

    public function test_a_closed_drawer_never_intercepts_taps_on_small_screens(): void
    {
        // The drawer root covers the whole viewport, so while it is closed it has to
        // stop hit testing and drop out of the tab order. Without inert plus the
        // pointer-events swap, the invisible wrapper swallows every tap underneath.
        $closedRoot = 'data-drawer-root inert class="pointer-events-none fixed inset-0 z-50 lg:hidden data-[open=true]:pointer-events-auto"';

        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        foreach (['/', '/games', '/accounts', '/admin'] as $uri) {
            $html = preg_replace('/\s+/', ' ', $this->get($uri)->assertOk()->getContent());

            $this->assertStringContainsString($closedRoot, $html, "The closed drawer on {$uri} still covers the page.");
        }
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

    public function test_the_home_hero_falls_back_to_the_gradient_panel_without_games(): void
    {
        // Nothing to slide, so the marquee slot becomes the branded panel
        // instead of an empty frame the driver would have to measure its way out
        // of.
        $this->get('/')
            ->assertOk()
            ->assertSee('Belum ada game di etalase')
            ->assertDontSee('data-game-marquee', escape: false);
    }

    public function test_the_home_hero_marquee_renders_one_set_covering_every_game(): void
    {
        Storage::fake('public');

        $games = Game::factory()->count(5)->create();
        $games->first()->update([
            'image' => UploadedFile::fake()->create('cover.jpg', 64, 'image/jpeg')->store('games', 'public'),
        ]);

        $html = $this->get('/')
            ->assertOk()
            ->assertSee($games->first()->refresh()->image_url, false)
            ->assertDontSee('Belum ada game di etalase')
            ->getContent();

        // Exactly one set, because the loop has to travel exactly one set width
        // to stay seamless. Repeating the list in Blade would ship every game
        // twice and double that distance.
        $this->assertSame(1, substr_count($html, 'data-game-marquee-set'));
        $this->assertSame(1, substr_count($html, 'data-game-marquee-track'));

        // Cover-less games fall back to their initial, so the set keeps one
        // tile per game rather than thinning out.
        foreach ($games as $game) {
            $this->assertStringContainsString(route('games.show', $game), $html);
        }
    }

    public function test_the_home_hero_marquee_renders_for_a_single_game(): void
    {
        $game = Game::factory()->create(['name' => 'Satu Game Saja']);

        $html = $this->get('/')
            ->assertOk()
            ->assertSee('Satu Game Saja')
            ->getContent();

        // One set, and the game links once inside it. The driver repeats the set
        // in the browser until two sets span the frame, so a one-game storefront
        // still never shows blank paper without the server shipping duplicates.
        $this->assertSame(1, substr_count($html, 'data-game-marquee-set'));

        // Twice in total: the hero set, then the "Pilih Game" strip below.
        $this->assertSame(2, substr_count($html, route('games.show', $game)));
    }

    public function test_the_home_hero_marquee_ships_a_pause_control_and_no_stepped_scroll(): void
    {
        Game::factory()->count(3)->create();

        $html = $this->get('/')->assertOk()->getContent();

        // WCAG 2.2.2 wants a user-controlled stop on motion that runs past five
        // seconds, and the loop must stay one uninterrupted CSS animation. A
        // stepped scroll carousel is what produced the visible pause and jump,
        // so guard against it coming back.
        $this->assertStringContainsString('data-game-marquee-toggle', $html);
        $this->assertStringNotContainsString('data-carousel', $html);
        $this->assertStringNotContainsString('setInterval', $html);
    }

    public function test_a_low_stock_warning_appears_on_the_homepage(): void
    {
        $game = Game::factory()->create();
        Account::factory()->count(2)->for($game)->create();

        $this->get('/')
            ->assertOk()
            ->assertSee('Stok tinggal 2 akun')
            ->assertSee('role="status"', escape: false);
    }

    public function test_the_homepage_shows_no_stock_warning_when_stock_is_healthy(): void
    {
        $game = Game::factory()->create();
        Account::factory()->count(6)->for($game)->create();

        $this->get('/')->assertOk()->assertDontSee('Stok tinggal');
    }

    public function test_the_reserved_filter_explains_why_an_account_is_on_hold(): void
    {
        $game = Game::factory()->create();
        Account::factory()->for($game)->reserved()->create();

        $this->get("/games/{$game->slug}?status=".AccountStatus::Reserved->value)
            ->assertOk()
            ->assertSee('Status sedang dipesan');
    }

    public function test_each_status_gets_its_own_badge_colour(): void
    {
        $game = Game::factory()->create();
        Account::factory()->for($game)->create();
        Account::factory()->for($game)->sold()->create();
        Account::factory()->for($game)->reserved()->create();

        $html = $this->get('/accounts?status=sold')->assertOk()->getContent();

        $this->assertStringContainsString('bg-pop-slate', $html);
        $this->assertStringNotContainsString('bg-pop-tangerine', $html);

        $html = $this->get('/accounts?status=reserved')->assertOk()->getContent();

        $this->assertStringContainsString('bg-pop-tangerine', $html);
    }

    public function test_an_unavailable_account_card_offers_an_alternative_instead_of_a_dead_button(): void
    {
        $game = Game::factory()->create();
        $account = Account::factory()->for($game)->sold()->create();

        $this->get('/accounts?status=sold')
            ->assertOk()
            ->assertSee('cari akun lain')
            ->assertDontSee($account->whatsappUrl(), false);
    }

    public function test_the_login_screen_keeps_the_slate_theme_inside_the_new_token_system(): void
    {
        // The storefront resolves the shared components against Bright Pop;
        // login and admin opt back into slate. This guards the opt-out class,
        // because losing it would repaint the admin panel violet.
        $this->get('/login')
            ->assertOk()
            ->assertSee('theme-slate', escape: false);

        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $this->get('/admin')
            ->assertOk()
            ->assertSee('theme-slate', escape: false);
    }

    public function test_public_pages_expose_an_image_free_fallback_for_missing_covers(): void
    {
        $game = Game::factory()->create(['name' => 'Ash Echoes']);

        $this->get('/games')->assertOk()->assertSee('Ash Echoes');
        $this->get("/games/{$game->slug}")->assertOk()->assertSee('Ash Echoes');
    }
}
