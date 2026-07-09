<?php

namespace Tests\Feature\EventFavorites;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_favorites_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/events/{$event->id}/favorite");

        $response->assertCreated();
        $this->assertDatabaseHas('event_favorites', [
            'fav_event_id' => $event->id,
            'fav_user_id' => $user->id,
        ]);
    }

    public function test_favoriting_same_event_twice_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson("/api/events/{$event->id}/favorite")->assertCreated();
        $this->postJson("/api/events/{$event->id}/favorite")->assertCreated();

        $this->assertDatabaseCount('event_favorites', 1);
    }

    public function test_unauthenticated_cannot_favorite_event(): void
    {
        $event = Event::factory()->create();

        $response = $this->postJson("/api/events/{$event->id}/favorite");

        $response->assertStatus(401);
    }

    public function test_favoriting_nonexistent_event_returns_404(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/events/999999/favorite');

        $response->assertStatus(404);
    }
}
