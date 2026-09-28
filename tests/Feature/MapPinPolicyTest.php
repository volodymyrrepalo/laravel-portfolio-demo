<?php

namespace Tests\Feature;

use App\Models\MapPin;
use App\Models\User;
use App\Policies\MapPinPolicy;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class MapPinPolicyTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_owner_can_view_update_and_delete_their_map_pin(): void
    {
        $owner = User::factory()->create();
        $mapPin = MapPin::factory()->for($owner)->create();
        $policy = app(MapPinPolicy::class);

        $this->assertTrue($policy->view($owner, $mapPin));
        $this->assertTrue($policy->update($owner, $mapPin));
        $this->assertTrue($policy->delete($owner, $mapPin));
    }

    public function test_non_owner_cannot_view_update_or_delete_a_map_pin(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $mapPin = MapPin::factory()->for($owner)->create();
        $policy = app(MapPinPolicy::class);

        $this->assertFalse($policy->view($otherUser, $mapPin));
        $this->assertFalse($policy->update($otherUser, $mapPin));
        $this->assertFalse($policy->delete($otherUser, $mapPin));
    }
}
