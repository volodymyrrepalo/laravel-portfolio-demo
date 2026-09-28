<?php

namespace Tests\Feature;

use App\Models\MapPin;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\URL;
use Livewire\Livewire;
use Tests\TestCase;

class MapAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_map_renders_the_map_access_form(): void
    {
        $this->get('/map')
            ->assertSeeText('Map access')
            ->assertSeeText('Sign in')
            ->assertSee('href="'.route('map').'"', false);
    }

    public function test_unverified_user_is_redirected_to_the_verification_notice_from_map(): void
    {
        $user = User::factory()->unverified()->create();

        $this->actingAs($user)
            ->get('/map')
            ->assertRedirectToRoute('verification.notice')
            ->assertSessionHas('auth.access.intended_route', 'map');
    }

    public function test_verified_user_can_view_their_map(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get('/map')
            ->assertSeeText('Your mushroom map')
            ->assertSeeText('Filter your pins')
            ->assertSeeText('Use my location')
            ->assertSee('aria-current="page"', false);
    }

    public function test_successful_map_sign_in_redirects_to_map(): void
    {
        $user = User::factory()->create(['email' => 'moss@example.test']);

        Livewire::test('auth.profile-access', ['intendedRoute' => 'map'])
            ->set('loginEmail', 'moss@example.test')
            ->set('loginPassword', 'password')
            ->call('login')
            ->assertRedirect(route('map'));

        $this->assertAuthenticatedAs($user);
    }

    public function test_map_verification_confirmation_returns_to_map_in_the_same_browser(): void
    {
        $user = User::factory()->unverified()->create(['name' => 'Moss Lantern']);
        $url = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(30),
            ['id' => $user->id, 'hash' => sha1($user->email)],
        );

        $this->withSession(['auth.access.intended_route' => 'map'])
            ->get($url)
            ->assertSeeText('Go to Map')
            ->assertSee('href="'.route('map').'"', false);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->assertAuthenticatedAs($user);
    }

    public function test_user_can_create_update_and_delete_their_own_map_pin(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('map.pin-manager')
            ->call('beginCreating', 50.4501, 30.5234)
            ->set('comment', 'Golden chanterelles under an oak tree')
            ->call('save')
            ->assertSet('statusMessage', 'Pin saved to your map.')
            ->assertDispatched('map-pins-updated');

        $mapPin = MapPin::query()->sole();

        $this->assertDatabaseHas('map_pins', [
            'id' => $mapPin->id,
            'user_id' => $user->id,
            'comment' => 'Golden chanterelles under an oak tree',
        ]);

        Livewire::actingAs($user)
            ->test('map.pin-manager')
            ->call('selectPin', $mapPin->id)
            ->set('comment', 'Updated note')
            ->call('save')
            ->assertSet('statusMessage', 'Pin comment updated.')
            ->assertDispatched('map-pins-updated');

        $this->assertDatabaseHas('map_pins', [
            'id' => $mapPin->id,
            'comment' => 'Updated note',
        ]);

        Livewire::actingAs($user)
            ->test('map.pin-manager')
            ->call('selectPin', $mapPin->id)
            ->call('deleteSelectedPin')
            ->assertSet('statusMessage', 'Pin removed from your map.')
            ->assertDispatched('map-pins-updated');

        $this->assertModelMissing($mapPin);
    }

    public function test_pin_creation_rejects_coordinates_outside_the_map_bounds(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('map.pin-manager')
            ->call('beginCreating', 95, 200)
            ->call('save')
            ->assertHasErrors([
                'latitude' => ['between'],
                'longitude' => ['between'],
            ]);

        $this->assertDatabaseCount('map_pins', 0);
    }

    public function test_user_is_forbidden_from_selecting_another_users_map_pin(): void
    {
        $owner = User::factory()->create();
        $viewer = User::factory()->create();
        $mapPin = MapPin::factory()->for($owner)->create();

        Livewire::actingAs($viewer)
            ->test('map.pin-manager')
            ->call('selectPin', $mapPin->id)
            ->assertForbidden();

        $this->assertDatabaseHas('map_pins', ['id' => $mapPin->id]);
    }

    public function test_map_component_only_serializes_the_current_users_pins(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $visiblePin = MapPin::factory()->for($user)->create(['comment' => 'Golden chanterelle']);
        $hiddenPin = MapPin::factory()->for($otherUser)->create(['comment' => 'Fly agaric']);

        Livewire::actingAs($user)
            ->test('map.pin-manager')
            ->assertSee($visiblePin->comment)
            ->assertDontSee($hiddenPin->comment)
            ->assertSee('x-on:input="filterPins()"', false);
    }
}
