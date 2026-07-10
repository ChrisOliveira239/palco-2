<?php

namespace Tests\Feature\Admin\Events;

use App\Models\City;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_updates_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        $city = City::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/admin/events/{$event->id}", [
            'title' => 'Novo título',
            'synopsis' => null,
            'type' => 'oficina',
            'venue_name' => 'Novo local',
            'city_id' => $city->id,
            'ticket_url' => null,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.title', 'Novo título');
        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_title' => 'Novo título']);
    }

    public function test_update_nonexistent_event_returns_404(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->putJson('/api/admin/events/99999', [
            'title' => 'Novo título',
            'type' => 'oficina',
            'venue_name' => 'Novo local',
            'city_id' => $city->id,
        ]);

        $response->assertStatus(404);
    }

    public function test_non_admin_cannot_update_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $city = City::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/admin/events/{$event->id}", [
            'title' => 'Novo título',
            'type' => 'oficina',
            'venue_name' => 'Novo local',
            'city_id' => $city->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_admin_updates_inactive_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create(['eve_active' => false]);
        $city = City::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/admin/events/{$event->id}", [
            'title' => 'Novo título',
            'type' => 'oficina',
            'venue_name' => 'Novo local',
            'city_id' => $city->id,
        ]);

        $response->assertOk();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_title' => 'Novo título', 'eve_active' => false]);
    }
}