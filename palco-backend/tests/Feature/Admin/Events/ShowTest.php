<?php

namespace Tests\Feature\Admin\Events;

use App\Models\City;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_shows_event(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::factory()->create();
        $event = Event::factory()->create(['eve_city_id' => $city->id, 'eve_title' => 'Show de Rock']);
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/admin/events/{$event->id}");

        $response->assertOk();
        $response->assertJsonPath('data.id', $event->id);
        $response->assertJsonPath('data.title', 'Show de Rock');
        $response->assertJsonPath('data.city.id', $city->id);
    }

    public function test_non_admin_cannot_show_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/admin/events/{$event->id}");

        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_show_event(): void
    {
        $event = Event::factory()->create();

        $response = $this->getJson("/api/admin/events/{$event->id}");

        $response->assertStatus(401);
    }

    public function test_showing_nonexistent_event_returns_404(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/admin/events/99999');

        $response->assertStatus(404);
    }

    public function test_admin_shows_inactive_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create(['eve_active' => false]);
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/admin/events/{$event->id}");

        $response->assertOk();
        $response->assertJsonPath('data.id', $event->id);
    }
}