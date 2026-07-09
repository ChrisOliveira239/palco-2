<?php

namespace Tests\Feature\EventFavorites;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_unfavorites_previously_favorited_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $event->favoritedBy()->attach($user->id);
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/events/{$event->id}/favorite");

        $response->assertNoContent();
        $this->assertDatabaseMissing('event_favorites', [
            'fav_event_id' => $event->id,
            'fav_user_id' => $user->id,
        ]);
    }

    public function test_unfavoriting_event_never_favorited_does_not_error(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/events/{$event->id}/favorite");

        $response->assertNoContent();
    }

    public function test_unauthenticated_cannot_unfavorite_event(): void
    {
        $event = Event::factory()->create();

        $response = $this->deleteJson("/api/events/{$event->id}/favorite");

        $response->assertStatus(401);
    }

    public function test_unfavoriting_nonexistent_event_returns_404(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson('/api/events/999999/favorite');

        $response->assertStatus(404);
    }
}