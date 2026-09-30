<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class WiringTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login_from_every_admin_route(): void
    {
        $routes = [
            ['get', '/admin'],
            ['get', '/admin/games'],
            ['get', '/admin/games/create'],
            ['get', '/admin/games/1/edit'],
            ['put', '/admin/games/1'],
            ['delete', '/admin/games/1'],
            ['get', '/admin/accounts'],
            ['get', '/admin/accounts/create'],
            ['post', '/admin/accounts'],
            ['get', '/admin/accounts/1/edit'],
            ['put', '/admin/accounts/1'],
            ['delete', '/admin/accounts/1'],
            ['post', '/admin/accounts/1/images'],
            ['patch', '/admin/accounts/1/images/1'],
            ['delete', '/admin/accounts/1/images/1'],
            ['get', '/admin/settings'],
            ['put', '/admin/settings'],
        ];

        foreach ($routes as [$method, $uri]) {
            $this->{$method}($uri)->assertRedirect(route('login'));
        }
    }

    public function test_non_admin_users_are_forbidden(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'customer']));

        $this->get('/admin')->assertForbidden();
        $this->get('/admin/accounts')->assertForbidden();
    }

    public function test_account_image_routes_are_admin_only(): void
    {
        Storage::fake('public');

        $account = Account::factory()->create();
        $image = $account->images()->create(['path' => 'accounts/a.jpg', 'is_cover' => true]);

        $this->actingAs(User::factory()->create(['role' => 'customer']));

        $this->post("/admin/accounts/{$account->id}/images", [
            'images' => [UploadedFile::fake()->create('sisip.jpg', 64, 'image/jpeg')],
        ])->assertForbidden();

        $this->patch("/admin/accounts/{$account->id}/images/{$image->id}")->assertForbidden();
        $this->delete("/admin/accounts/{$account->id}/images/{$image->id}")->assertForbidden();

        $this->assertTrue($image->fresh()->is_cover);
        $this->assertSame(1, $account->images()->count());
        Storage::disk('public')->assertMissing('accounts/sisip.jpg');
    }

    public function test_authenticated_admins_pass_the_middleware(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $response = $this->get('/admin');

        $this->assertNotSame(302, $response->getStatusCode());
        $this->assertNotSame(403, $response->getStatusCode());
    }

    public function test_login_rejects_bad_credentials(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN, 'password' => 'correct-password']);

        $this->post('/login', ['email' => $user->email, 'password' => 'wrong-password'])
            ->assertSessionHasErrors('email');

        $this->assertGuest();
    }

    public function test_login_succeeds_and_lands_on_the_dashboard(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN, 'password' => 'correct-password']);

        $this->post('/login', ['email' => $user->email, 'password' => 'correct-password']);

        $this->assertAuthenticatedAs($user);
    }

    public function test_game_store_validates_input(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $this->post('/admin/games', [
            'name' => '',
            'slug' => 'Not A Slug!',
            'status' => 'bogus',
            'description' => str_repeat('x', 5001),
        ])->assertSessionHasErrors(['name', 'slug', 'status', 'description']);

        $this->assertSame(0, Game::query()->count());
    }

    public function test_game_store_rejects_a_duplicate_slug(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
        Game::factory()->create(['slug' => 'mobile-legends']);

        $this->post('/admin/games', [
            'name' => 'Mobile Legends',
            'slug' => 'mobile-legends',
            'status' => 'active',
        ])->assertSessionHasErrors('slug');
    }

    public function test_game_store_fills_a_missing_slug_from_the_name(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $this->post('/admin/games', [
            'name' => 'Mobile Legends',
            'slug' => '',
            'status' => 'active',
        ]);

        $this->assertDatabaseHas('games', ['slug' => 'mobile-legends']);
    }

    public function test_account_store_validates_input(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));

        $this->post('/admin/accounts', [
            'account_code' => 'ml 001',
            'game_id' => 999,
            'title' => '',
            'price' => 'abc',
            'status' => 'pending',
        ])->assertSessionHasErrors(['account_code', 'game_id', 'title', 'price', 'status']);

        $this->assertSame(0, Account::query()->count());
    }

    public function test_account_store_uppercases_the_code(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
        $game = Game::factory()->create();

        $this->post('/admin/accounts', [
            'game_id' => $game->id,
            'account_code' => ' ml-001 ',
            'title' => 'Akun Sultan',
            'price' => 150000,
            'status' => 'available',
        ]);

        $this->assertDatabaseHas('accounts', ['account_code' => 'ML-001']);
    }

    public function test_a_game_with_accounts_cannot_be_deleted(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
        $game = Game::factory()->create();
        Account::factory()->for($game)->count(2)->create();

        $this->delete("/admin/games/{$game->id}")->assertSessionHas('error');

        $this->assertDatabaseHas('games', ['id' => $game->id]);
    }

    public function test_an_empty_game_can_be_deleted(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]));
        $game = Game::factory()->create();

        $this->delete("/admin/games/{$game->id}");

        $this->assertDatabaseMissing('games', ['id' => $game->id]);
    }

    public function test_settings_update_ignores_a_blank_password(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN, 'name' => 'Old Name']);
        $originalPassword = $user->password;
        $this->actingAs($user);

        $this->put('/admin/settings', ['name' => 'New Name', 'email' => $user->email, 'password' => '']);

        $user->refresh();

        $this->assertSame($originalPassword, $user->password);
        $this->assertSame('New Name', $user->name);
    }
}
