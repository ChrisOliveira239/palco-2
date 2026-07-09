<?php

namespace Tests\Feature\EventSessions;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creates_event_session(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/events/{$event->id}/sessions", [
            'start_at' => now()->addDays(5)->toDateTimeString(),
            'end_at' => null,
            'pricing_type' => 'fixed',
            'price' => 50,
            'capacity' => 100,
        ]);

        $response->assertCreated();
        $response->assertJsonPath('data.pricing_type', 'fixed');
        $response->assertJsonPath('data.price', '50.00');
        $response->assertJsonPath('data.capacity', 100);

        $this->assertDatabaseHas('event_sessions', ['ses_event_id' => $event->id, 'ses_capacity' => 100]);
    }

    public function test_non_admin_cannot_create_event_session(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/events/{$event->id}/sessions", [
            'start_at' => now()->addDays(5)->toDateTimeString(),
            'pricing_type' => 'free',
        ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_create_event_session(): void
    {
        $event = Event::factory()->create();

        $response = $this->postJson("/api/events/{$event->id}/sessions", [
            'start_at' => now()->addDays(5)->toDateTimeString(),
            'pricing_type' => 'free',
        ]);

        $response->assertStatus(401);
    }

    public function test_store_requires_mandatory_fields(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/events/{$event->id}/sessions", []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['start_at', 'pricing_type']);
    }

    public function test_fixed_pricing_requires_price(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/events/{$event->id}/sessions", [
            'start_at' => now()->addDays(5)->toDateTimeString(),
            'pricing_type' => 'fixed',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['price']);
    }

    public function test_end_at_before_start_at_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/events/{$event->id}/sessions", [
            'start_at' => now()->addDays(5)->toDateTimeString(),
            'end_at' => now()->addDays(4)->toDateTimeString(),
            'pricing_type' => 'free',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['end_at']);
    }
}