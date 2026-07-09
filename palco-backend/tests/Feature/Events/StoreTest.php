<?php

namespace Tests\Feature\Events;

use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creates_event(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/events', [
            'title' => 'Show de Rock',
            'synopsis' => 'Uma noite de rock.',
            'type' => 'show',
            'venue_name' => 'Arena X',
            'city_id' => $city->id,
            'ticket_url' => null,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.title', 'Show de Rock');
        $response->assertJsonPath('data.city.id', $city->id);

        $this->assertDatabaseHas('events', ['eve_title' => 'Show de Rock', 'eve_created_by_id' => $admin->id]);
    }

    public function test_non_admin_cannot_create_event(): void
    {
        $user = User::factory()->create();
        $city = City::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/events', [
            'title' => 'Show de Rock',
            'type' => 'show',
            'venue_name' => 'Arena X',
            'city_id' => $city->id,
        ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_create_event(): void
    {
        $city = City::factory()->create();

        $response = $this->postJson('/api/events', [
            'title' => 'Show de Rock',
            'type' => 'show',
            'venue_name' => 'Arena X',
            'city_id' => $city->id,
        ]);

        $response->assertStatus(401);
    }

    public function test_store_requires_mandatory_fields(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/events', []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['title', 'type', 'venue_name', 'city_id']);
    }
}