<?php

namespace Tests\Feature;

use App\Models\MapPin;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class FavoritesAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_favorites_renders_the_access_form(): void
    {
        $this->get('/favorites')
            ->assertSeeText('Favorites access')
            ->assertSeeText('Sign in')
            ->assertSee('href="'.route('favorites').'"', false);
    }

    public function test_unverified_user_is_redirected_to_the_verification_notice_from_favorites(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/favorites')
            ->assertRedirectToRoute('verification.notice')
            ->assertSessionHas('auth.access.intended_route', 'favorites');
    }

    public function test_verified_user_can_view_their_favorites(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/favorites')
            ->assertSeeText('Favorite finds')
            ->assertSeeText('No favorite pins yet.')
            ->assertSee('aria-current="page"', false);
    }

    public function test_successful_favorites_sign_in_redirects_to_favorites(): void
    {
        $user = User::factory()->create(['email' => 'moss@example.test']);

        Livewire::test('auth.profile-access', ['intendedRoute' => 'favorites'])
            ->set('loginEmail', 'moss@example.test')
            ->set('loginPassword', 'password')
            ->call('login')
            ->assertRedirect(route('favorites'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_favorites_verification_confirmation_returns_to_favorites_in_the_same_browser(): void
    {
        $user = User::factory()->unverified()->create(['name' => 'Moss Lantern']);
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->withSession(['auth.access.intended_route' => 'favorites'])
            ->get($url)
            ->assertSeeText('Go to Favorites')
            ->assertSee('href="'.route('favorites').'"', false);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertAuthenticatedAs($user);
    }

    public function test_favorites_displays_only_the_current_users_pins_in_favorite_order(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $olderFavorite = MapPin::factory()->for($user)->create(['comment' => 'Older favorite']);
        $newerFavorite = MapPin::factory()->for($user)->create(['comment' => 'Newer favorite']);
        $otherFavorite = MapPin::factory()->for($otherUser)->create(['comment' => 'Other users favorite']);
        $user->favoriteMapPins()->attach($olderFavorite, [
            'created_at' => now()->subMinute(),
            'updated_at' => now()->subMinute(),
        ]);
        $user->favoriteMapPins()->attach($newerFavorite, [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $otherUser->favoriteMapPins()->attach($otherFavorite);

        $this->actingAs($user)
            ->get('/favorites')
            ->assertSeeInOrder(['Newer favorite', 'Older favorite'])
            ->assertDontSee('Other users favorite')
            ->assertSee('href="'.route('map', ['pin' => $newerFavorite]).'"', false)
            ->assertSeeText('Remove from favorites');
    }

    public function test_user_can_remove_a_favorite_without_deleting_the_pin(): void
    {
        $user = User::factory()->create();
        $mapPin = MapPin::factory()->for($user)->create();
        $user->favoriteMapPins()->attach($mapPin);

        Livewire::actingAs($user)
            ->test('favorites.pin-list')
            ->call('removeFavorite', $mapPin->id)
            ->assertSee('Pin removed from your favorites.')
            ->assertSee('No favorite pins yet.');

        $this->assertDatabaseMissing('map_pin_favorites', [
            'user_id' => $user->id,
            'map_pin_id' => $mapPin->id,
        ]);
        $this->assertModelExists($mapPin);
    }

    public function test_user_cannot_remove_another_users_favorite(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $mapPin = MapPin::factory()->for($otherUser)->create();
        $otherUser->favoriteMapPins()->attach($mapPin);

        try {
            Livewire::actingAs($user)
                ->test('favorites.pin-list')
                ->call('removeFavorite', $mapPin->id);

            $this->fail('Removing another user\'s favorite should fail.');
        } catch (ModelNotFoundException) {
        }

        $this->assertDatabaseHas('map_pin_favorites', [
            'user_id' => $otherUser->id,
            'map_pin_id' => $mapPin->id,
        ]);
    }

    public function test_favorite_card_escapes_pin_comments(): void
    {
        $user = User::factory()->create();
        $mapPin = MapPin::factory()->for($user)->create(['comment' => '<script>alert(1)</script>']);
        $user->favoriteMapPins()->attach($mapPin);

        $this->actingAs($user)
            ->get('/favorites')
            ->assertDontSee('<script>alert(1)</script>', false);
    }

    public function test_owned_map_focus_pin_is_passed_to_the_map_component(): void
    {
        $user = User::factory()->create();
        $mapPin = MapPin::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('map', ['pin' => $mapPin]))
            ->assertSee('mapPinManager($wire,', false)
            ->assertSee(', '.$mapPin->id.')', false);
    }

    public function test_other_users_map_focus_pin_falls_back_to_the_default_map_view(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $mapPin = MapPin::factory()->for($otherUser)->create();

        $this->actingAs($user)
            ->get(route('map', ['pin' => $mapPin]))
            ->assertSee('mapPinManager($wire,', false)
            ->assertSee(', null)', false);
    }
}
