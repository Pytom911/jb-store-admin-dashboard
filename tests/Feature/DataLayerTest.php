<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\GameStatus;
use App\Models\Account;
use App\Models\AccountImage;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class DataLayerTest extends TestCase
{
    use RefreshDatabase;

    public function test_migrations_create_the_expected_schema(): void
    {
        $this->assertTrue(Schema::hasTable('users'));
        $this->assertTrue(Schema::hasTable('games'));
        $this->assertTrue(Schema::hasTable('accounts'));
        $this->assertTrue(Schema::hasTable('account_images'));
        $this->assertTrue(Schema::hasColumns('accounts', ['account_code', 'game_id', 'title', 'description', 'price', 'status']));
        $this->assertTrue(Schema::hasColumns('account_images', ['account_id', 'path', 'is_cover']));
        $this->assertTrue(Schema::hasColumns('users', ['role']));
    }

    public function test_seeders_create_the_demo_data(): void
    {
        $this->seed();

        $this->assertSame(3, Game::query()->count());
        $this->assertSame(7, Account::query()->count());
        $this->assertSame(1, User::query()->where('role', 'admin')->count());
    }

    public function test_accounts_no_longer_store_credentials(): void
    {
        $this->assertFalse(Schema::hasColumn('accounts', 'username'));
        $this->assertFalse(Schema::hasColumn('accounts', 'password'));
    }

    public function test_an_account_without_images_has_no_cover(): void
    {
        $account = Account::factory()->create();

        $this->assertCount(0, $account->images);
        $this->assertNull($account->coverImage);
    }

    public function test_cover_image_resolves_to_the_flagged_row(): void
    {
        $account = Account::factory()->create();

        $account->images()->create(['path' => 'accounts/pertama.jpg', 'is_cover' => false]);
        $second = $account->images()->create(['path' => 'accounts/kedua.jpg', 'is_cover' => true]);

        $this->assertTrue($second->is($account->fresh()->coverImage));
    }

    public function test_remaining_image_slots_account_for_existing_images(): void
    {
        $account = Account::factory()->create();

        $this->assertSame(AccountImage::MAX_PER_ACCOUNT, $account->remainingImageSlots());

        $account->images()->createMany([
            ['path' => 'accounts/a.jpg', 'is_cover' => true],
            ['path' => 'accounts/b.jpg', 'is_cover' => false],
        ]);

        $this->assertSame(AccountImage::MAX_PER_ACCOUNT - 2, $account->fresh()->remainingImageSlots());
    }

    public function test_public_filters_and_sorting(): void
    {
        $gameA = Game::factory()->create(['name' => 'Alpha']);
        $gameB = Game::factory()->create(['name' => 'Beta']);

        Account::factory()->for($gameA)->create(['account_code' => 'AA-001', 'title' => 'Sultan Zero', 'price' => 100000, 'status' => AccountStatus::Available]);
        Account::factory()->for($gameA)->create(['account_code' => 'AA-002', 'title' => 'Cheap One', 'price' => 10000, 'status' => AccountStatus::Available]);
        Account::factory()->for($gameB)->create(['account_code' => 'BB-001', 'title' => 'Sold One', 'price' => 50000, 'status' => AccountStatus::Sold]);

        $this->assertSame(['AA-002', 'AA-001'], Account::query()->available()->sorted('price_asc')->pluck('account_code')->all());
        $this->assertSame(['AA-001', 'AA-002'], Account::query()->available()->sorted('price_desc')->pluck('account_code')->all());
        $this->assertSame(['AA-001'], Account::query()->search('AA-001')->pluck('account_code')->all());
        $this->assertSame(['AA-001'], Account::query()->search('sultan')->pluck('account_code')->all());
        $this->assertSame(0, Account::query()->search('nothing-here')->count());

        $this->assertSame(1, Account::query()->whereHas('game', fn ($q) => $q->where('slug', $gameB->slug))->count());
    }

    public function test_stock_counts_are_computed_per_game(): void
    {
        $game = Game::factory()->create();

        Account::factory()->for($game)->count(3)->available()->create();
        Account::factory()->for($game)->count(2)->sold()->create();

        $game = Game::query()->withStockCounts()->find($game->id);

        $this->assertSame(5, $game->accounts_count);
        $this->assertSame(3, $game->available_accounts_count);
    }

    public function test_deleting_a_game_cascades_to_its_accounts(): void
    {
        $game = Game::factory()->create();
        Account::factory()->for($game)->count(2)->create();

        $game->delete();

        $this->assertSame(0, Account::query()->where('game_id', $game->id)->count());
    }

    public function test_relationships(): void
    {
        $game = Game::factory()->create();
        $account = Account::factory()->for($game)->available()->create();

        $this->assertTrue($game->is($account->game));
        $this->assertTrue($account->is($game->accounts->first()));
        $this->assertCount(1, $game->availableAccounts);
        $this->assertSame(AccountStatus::Available, $account->status);
        $this->assertTrue($account->isAvailable());
    }

    public function test_deleting_an_account_cascades_to_its_images(): void
    {
        $account = Account::factory()->create();

        $account->images()->createMany([
            ['path' => 'accounts/a.jpg', 'is_cover' => true],
            ['path' => 'accounts/b.jpg', 'is_cover' => false],
        ]);

        $this->assertSame(2, AccountImage::query()->where('account_id', $account->id)->count());

        $account->delete();

        $this->assertSame(0, AccountImage::query()->where('account_id', $account->id)->count());
    }

    public function test_whatsapp_url_contains_the_account_code(): void
    {
        config(['marketplace.whatsapp_number' => '628999111222']);

        $url = Account::factory()->create(['account_code' => 'ML-001'])->whatsappUrl();

        $this->assertStringStartsWith('https://wa.me/628999111222?text=', $url);
        $this->assertStringContainsString(urlencode('Halo, saya tertarik dengan akun ML-001.'), $url);
    }

    public function test_active_scope_and_inactive_factory_state(): void
    {
        Game::factory()->create();
        Game::factory()->inactive()->create();

        $this->assertSame(1, Game::query()->active()->count());
        $this->assertSame(GameStatus::Inactive, Game::query()->withoutGlobalScopes()->where('status', 'inactive')->first()->status);
    }
}
