<?php

namespace Tests\Feature;

use App\Enums\AccountStatus;
use App\Enums\GameStatus;
use App\Models\Account;
use App\Models\AccountImage;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminCrudTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    }

    public function test_every_admin_page_renders(): void
    {
        $game = Game::factory()->create();
        $account = Account::factory()->for($game)->create();

        $this->actingAs($this->admin);

        $this->get('/admin')->assertOk()->assertViewIs('admin.dashboard');
        $this->get('/admin/games')->assertOk()->assertViewIs('admin.games.index');
        $this->get('/admin/games/create')->assertOk()->assertViewIs('admin.games.create');
        $this->get("/admin/games/{$game->id}/edit")->assertOk()->assertViewIs('admin.games.edit');
        $this->get('/admin/accounts')->assertOk()->assertViewIs('admin.accounts.index');
        $this->get('/admin/accounts/create')->assertOk()->assertViewIs('admin.accounts.create');
        $this->get("/admin/accounts/{$account->id}")->assertOk()->assertViewIs('admin.accounts.show');
        $this->get("/admin/accounts/{$account->id}/edit")->assertOk()->assertViewIs('admin.accounts.edit');
        $this->get('/admin/settings')->assertOk()->assertViewIs('admin.settings');
    }

    public function test_the_login_page_renders_for_guests(): void
    {
        $this->get('/login')->assertOk()->assertViewIs('auth.login');
    }

    public function test_an_authenticated_admin_is_redirected_away_from_the_login_page(): void
    {
        $this->actingAs($this->admin)
            ->get('/login')
            ->assertRedirect(route('admin.dashboard'));
    }

    public function test_a_game_can_be_created(): void
    {
        $this->actingAs($this->admin)
            ->post('/admin/games', [
                'name' => 'Mobile Legends',
                'slug' => '',
                'status' => GameStatus::Active->value,
                'description' => 'Game MOBA paling populer.',
            ])
            ->assertRedirect(route('admin.games.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('games', [
            'name' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'status' => 'active',
        ]);
    }

    public function test_a_game_can_be_updated(): void
    {
        $game = Game::factory()->create(['name' => 'Old Name', 'status' => GameStatus::Active]);

        $this->actingAs($this->admin)
            ->put("/admin/games/{$game->id}", [
                'name' => 'PUBG Mobile',
                'slug' => 'pubg-mobile',
                'status' => GameStatus::Inactive->value,
            ])
            ->assertRedirect(route('admin.games.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('games', [
            'id' => $game->id,
            'name' => 'PUBG Mobile',
            'slug' => 'pubg-mobile',
            'status' => 'inactive',
        ]);
    }

    public function test_uploading_a_game_image_stores_it_on_the_public_disk(): void
    {
        Storage::fake('public');

        $this->actingAs($this->admin)
            ->post('/admin/games', [
                'name' => 'Free Fire',
                'status' => GameStatus::Active->value,
                'image' => UploadedFile::fake()->create('cover.jpg', 64, 'image/jpeg'),
            ])
            ->assertRedirect(route('admin.games.index'));

        $game = Game::query()->sole();

        $this->assertNotNull($game->image);
        Storage::disk('public')->assertExists($game->image);
    }

    public function test_an_empty_game_can_be_deleted(): void
    {
        $game = Game::factory()->create();

        $this->actingAs($this->admin)
            ->delete("/admin/games/{$game->id}")
            ->assertRedirect(route('admin.games.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }

    public function test_a_game_with_accounts_cannot_be_deleted(): void
    {
        $game = Game::factory()->create();
        Account::factory()->for($game)->create();

        $this->actingAs($this->admin)
            ->delete("/admin/games/{$game->id}")
            ->assertSessionHas('error');

        $this->assertDatabaseHas('games', ['id' => $game->id]);
    }

    public function test_an_account_can_be_created(): void
    {
        $game = Game::factory()->create();

        $this->actingAs($this->admin)
            ->post('/admin/accounts', [
                'game_id' => $game->id,
                'account_code' => 'ML-001',
                'title' => 'Akun Sultan',
                'description' => 'Sudah punya 120 skin.',
                'price' => 150000,
                'status' => AccountStatus::Available->value,
            ])
            ->assertRedirect(route('admin.accounts.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'game_id' => $game->id,
            'account_code' => 'ML-001',
            'title' => 'Akun Sultan',
            'status' => 'available',
        ]);
    }

    public function test_an_account_can_be_updated(): void
    {
        $account = Account::factory()->create(['status' => AccountStatus::Available]);

        $this->actingAs($this->admin)
            ->put("/admin/accounts/{$account->id}", [
                'game_id' => $account->game_id,
                'account_code' => 'ML-777',
                'title' => 'Judul Baru',
                'price' => 275000,
                'status' => AccountStatus::Sold->value,
            ])
            ->assertRedirect(route('admin.accounts.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'account_code' => 'ML-777',
            'title' => 'Judul Baru',
            'status' => 'sold',
        ]);
    }

    public function test_an_account_can_be_deleted(): void
    {
        $account = Account::factory()->create();

        $this->actingAs($this->admin)
            ->delete("/admin/accounts/{$account->id}")
            ->assertRedirect(route('admin.accounts.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('accounts', ['id' => $account->id]);
    }

    public function test_uploading_several_account_images_stores_them_on_the_public_disk(): void
    {
        Storage::fake('public');

        $game = Game::factory()->create();

        $this->actingAs($this->admin)
            ->post('/admin/accounts', [
                'game_id' => $game->id,
                'account_code' => 'ML-500',
                'title' => 'Akun Sultan',
                'price' => 150000,
                'status' => AccountStatus::Available->value,
                'cover_first' => '1',
                'images' => [
                    UploadedFile::fake()->create('satu.jpg', 64, 'image/jpeg'),
                    UploadedFile::fake()->create('dua.jpg', 64, 'image/jpeg'),
                    UploadedFile::fake()->create('tiga.jpg', 64, 'image/jpeg'),
                ],
            ])
            ->assertRedirect(route('admin.accounts.index'))
            ->assertSessionHas('success');

        $account = Account::query()->sole();

        $this->assertCount(3, $account->images);

        foreach ($account->images as $image) {
            Storage::disk('public')->assertExists($image->path);
            $this->assertStringStartsWith('accounts/', $image->path);
        }

        $this->assertTrue($account->coverImage->is($account->images->first()));
    }

    public function test_the_first_image_becomes_the_cover_even_without_the_checkbox(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();

        $this->actingAs($this->admin)->put("/admin/accounts/{$account->id}", [
            'game_id' => $account->game_id,
            'account_code' => $account->account_code,
            'title' => $account->title,
            'price' => $account->price,
            'status' => $account->status->value,
            'images' => [UploadedFile::fake()->create('baru.jpg', 64, 'image/jpeg')],
        ]);

        $account->refresh()->load('images');

        $this->assertCount(1, $account->images);
        $this->assertTrue($account->coverImage->is($account->images->first()));
    }

    public function test_uploading_more_images_does_not_displace_an_existing_cover(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();
        $existingCover = $account->images()->create(['path' => 'accounts/lama.jpg', 'is_cover' => true]);

        $this->actingAs($this->admin)->put("/admin/accounts/{$account->id}", [
            'game_id' => $account->game_id,
            'account_code' => $account->account_code,
            'title' => $account->title,
            'price' => $account->price,
            'status' => $account->status->value,
            'cover_first' => '1',
            'images' => [UploadedFile::fake()->create('baru.jpg', 64, 'image/jpeg')],
        ]);

        $account->refresh()->load('images');

        $this->assertCount(2, $account->images);
        $this->assertTrue($account->coverImage->is($existingCover));
    }

    public function test_account_image_upload_validates_type_and_size(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();

        $this->actingAs($this->admin)->put("/admin/accounts/{$account->id}", [
            'game_id' => $account->game_id,
            'account_code' => $account->account_code,
            'title' => $account->title,
            'price' => $account->price,
            'status' => $account->status->value,
            'images' => [UploadedFile::fake()->create('bukan-gambar.pdf', 64, 'application/pdf')],
        ])->assertSessionHasErrors('images.0');

        $this->actingAs($this->admin)->put("/admin/accounts/{$account->id}", [
            'game_id' => $account->game_id,
            'account_code' => $account->account_code,
            'title' => $account->title,
            'price' => $account->price,
            'status' => $account->status->value,
            'images' => [UploadedFile::fake()->create('besar.jpg', 4096, 'image/jpeg')],
        ])->assertSessionHasErrors('images.0');

        $this->assertCount(0, $account->fresh()->images);
    }

    public function test_an_account_cannot_exceed_the_image_limit(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();
        $account->images()->createMany(array_map(
            fn (int $index) => ['path' => "accounts/isi-{$index}.jpg", 'is_cover' => $index === 0],
            range(1, AccountImage::MAX_PER_ACCOUNT),
        ));

        $this->actingAs($this->admin)->put("/admin/accounts/{$account->id}", [
            'game_id' => $account->game_id,
            'account_code' => $account->account_code,
            'title' => $account->title,
            'price' => $account->price,
            'status' => $account->status->value,
            'images' => [UploadedFile::fake()->create('tambahan.jpg', 64, 'image/jpeg')],
        ])->assertSessionHasErrors('images');

        $this->assertCount(AccountImage::MAX_PER_ACCOUNT, $account->fresh()->images);
    }

    public function test_an_image_can_be_promoted_to_cover(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();
        $first = $account->images()->create(['path' => 'accounts/a.jpg', 'is_cover' => true]);
        $second = $account->images()->create(['path' => 'accounts/b.jpg', 'is_cover' => false]);

        $this->actingAs($this->admin)
            ->patch("/admin/accounts/{$account->id}/images/{$second->id}")
            ->assertSessionHas('success');

        $this->assertTrue($second->fresh()->is_cover);
        $this->assertFalse($first->fresh()->is_cover);
    }

    public function test_deleting_the_cover_promotes_the_oldest_remaining_image(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();
        $cover = $account->images()->create(['path' => 'accounts/a.jpg', 'is_cover' => true]);
        $remaining = $account->images()->create(['path' => 'accounts/b.jpg', 'is_cover' => false]);
        $spare = $account->images()->create(['path' => 'accounts/c.jpg', 'is_cover' => false]);

        $this->actingAs($this->admin)
            ->delete("/admin/accounts/{$account->id}/images/{$cover->id}")
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('account_images', ['id' => $cover->id]);
        Storage::disk('public')->assertMissing('accounts/a.jpg');

        $this->assertTrue($remaining->fresh()->is_cover);
        $this->assertFalse($spare->fresh()->is_cover);
    }

    public function test_an_image_belonging_to_another_account_cannot_be_touched(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();
        $other = Account::factory()->create();
        $image = $account->images()->create(['path' => 'accounts/a.jpg', 'is_cover' => true]);

        $this->actingAs($this->admin)
            ->patch("/admin/accounts/{$other->id}/images/{$image->id}")
            ->assertNotFound();

        $this->actingAs($this->admin)
            ->delete("/admin/accounts/{$other->id}/images/{$image->id}")
            ->assertNotFound();

        $this->assertDatabaseHas('account_images', ['id' => $image->id, 'is_cover' => true]);
    }

    public function test_the_admin_gallery_and_edit_form_render_existing_images(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create(['account_code' => 'ML-600']);
        $cover = $account->images()->create(['path' => 'accounts/sampul.jpg', 'is_cover' => true]);
        $detail = $account->images()->create(['path' => 'accounts/detail.jpg', 'is_cover' => false]);

        $this->actingAs($this->admin)->get("/admin/accounts/{$account->id}")
            ->assertOk()
            ->assertSee('Galeri')
            ->assertSee('Sampul')
            ->assertSee($cover->url, false)
            ->assertSee($detail->url, false)
            ->assertSee(route('admin.accounts.images.update', [$account, $detail]), false);

        $this->actingAs($this->admin)->get("/admin/accounts/{$account->id}/edit")
            ->assertOk()
            ->assertSee('Gambar saat ini (2)', false)
            ->assertSee($cover->url, false)
            ->assertSee('Sampul sudah ada', false);
    }

    public function test_the_image_list_shows_the_cover_and_remaining_count(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create(['account_code' => 'ML-601']);
        $account->images()->createMany([
            ['path' => 'accounts/a.jpg', 'is_cover' => true],
            ['path' => 'accounts/b.jpg', 'is_cover' => false],
            ['path' => 'accounts/c.jpg', 'is_cover' => false],
        ]);

        $this->actingAs($this->admin)->get('/admin/accounts')
            ->assertOk()
            ->assertSee('+2', false);
    }

    public function test_deleting_an_account_removes_its_image_files(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();
        $account->images()->create(['path' => 'accounts/a.jpg', 'is_cover' => true]);
        $account->images()->create(['path' => 'accounts/b.jpg', 'is_cover' => false]);

        $this->actingAs($this->admin)
            ->delete("/admin/accounts/{$account->id}")
            ->assertRedirect(route('admin.accounts.index'));

        Storage::disk('public')->assertMissing('accounts/a.jpg');
        Storage::disk('public')->assertMissing('accounts/b.jpg');
        $this->assertSame(0, AccountImage::query()->where('account_id', $account->id)->count());
    }

    public function test_the_account_list_can_be_searched_filtered_and_sorted(): void
    {
        $mobileLegends = Game::factory()->create(['name' => 'Mobile Legends']);
        $pubg = Game::factory()->create(['name' => 'PUBG Mobile']);

        Account::factory()->for($mobileLegends)->create(['account_code' => 'ML-100', 'price' => 500000, 'title' => 'Mewah']);
        Account::factory()->for($mobileLegends)->create(['account_code' => 'ML-200', 'price' => 100000, 'title' => 'Murah']);
        Account::factory()->for($pubg)->create(['account_code' => 'PUB-300', 'price' => 300000, 'status' => AccountStatus::Sold]);

        $this->actingAs($this->admin);

        $this->get('/admin/accounts?search=ML-1')
            ->assertOk()
            ->assertSee('ML-100')
            ->assertDontSee('ML-200');

        $this->get("/admin/accounts?game_id={$mobileLegends->id}")
            ->assertOk()
            ->assertSee('ML-100')
            ->assertSee('ML-200')
            ->assertDontSee('PUB-300');

        $this->get('/admin/accounts?status=sold')
            ->assertOk()
            ->assertSee('PUB-300')
            ->assertDontSee('ML-100');

        $sorted = $this->get('/admin/accounts?sort=price_asc')->assertOk();

        $codes = $sorted->viewData('accounts')->pluck('account_code')->all();

        $this->assertSame(['ML-200', 'PUB-300', 'ML-100'], $codes);
    }

    public function test_the_game_list_can_be_searched_and_filtered(): void
    {
        Game::factory()->create(['name' => 'Mobile Legends', 'status' => GameStatus::Active]);
        Game::factory()->create(['name' => 'PUBG Mobile', 'status' => GameStatus::Inactive]);

        $this->actingAs($this->admin);

        $this->get('/admin/games?search=Legends')
            ->assertOk()
            ->assertSee('Mobile Legends')
            ->assertDontSee('PUBG Mobile');

        $this->get('/admin/games?status=inactive')
            ->assertOk()
            ->assertSee('PUBG Mobile')
            ->assertDontSee('Mobile Legends');
    }

    public function test_the_dashboard_shows_live_counts(): void
    {
        $game = Game::factory()->create();

        Account::factory()->for($game)->count(3)->create(['status' => AccountStatus::Available]);
        Account::factory()->for($game)->count(2)->create(['status' => AccountStatus::Sold]);

        $this->actingAs($this->admin)
            ->get('/admin')
            ->assertOk()
            ->assertViewHas('totalGames', 1)
            ->assertViewHas('totalAccounts', 5)
            ->assertViewHas('availableAccounts', 3)
            ->assertViewHas('soldAccounts', 2);
    }

    public function test_lists_paginate_with_the_project_pagination_view(): void
    {
        $game = Game::factory()->create();
        Account::factory()->for($game)->count(25)->create();

        $response = $this->actingAs($this->admin)->get('/admin/accounts')->assertOk();

        $response->assertSee('Menampilkan');
        $response->assertSee('hasil');
        $response->assertSee('Berikutnya');
        $response->assertSee('aria-current="page"', escape: false);
        $response->assertSee('min-w-9');

        $this->assertCount(10, $response->viewData('accounts')->items());
        $this->assertSame(25, $response->viewData('accounts')->total());
    }

    public function test_the_admin_can_update_their_profile(): void
    {
        $this->actingAs($this->admin)
            ->put('/admin/settings', [
                'name' => 'Admin Baru',
                'email' => 'baru@akunstore.test',
            ])
            ->assertRedirect(route('admin.settings.edit'))
            ->assertSessionHas('success');

        $this->admin->refresh();

        $this->assertSame('Admin Baru', $this->admin->name);
        $this->assertSame('baru@akunstore.test', $this->admin->email);
    }

    public function test_an_admin_can_log_out(): void
    {
        $this->actingAs($this->admin)
            ->post('/logout')
            ->assertRedirect('/');

        $this->assertGuest();
    }
}
