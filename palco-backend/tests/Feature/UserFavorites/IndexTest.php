<?php

namespace Tests\Feature\UserFavorites;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_favorite_events_of_authenticated_user(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $event->favoritedBy()->attach($user->id);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user/favorites');

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $event->id);
    }

    public function test_does_not_return_events_favorited_by_other_users(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();
        $event->favoritedBy()->attach($otherUser->id);
        Sanctum::actingAs($user);

        $response = $this->getJson('/api/user/favorites');

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_unauthenticated_cannot_list_favorite_events(): void
    {
        $response = $this->getJson('/api/user/favorites');

        $response->assertStatus(401);
    }

    public function test_deactivated_favorite_event_disappears_from_list_but_pivot_row_remains(): void
    {
        $admin = User::factory()->admin()->create();
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $event->favoritedBy()->attach($user->id);
        Sanctum::actingAs($admin);
        $this->deleteJson("/api/admin/events/{$event->id}")->assertNoContent();

        Sanctum::actingAs($user);
        $response = $this->getJson('/api/user/favorites');

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
        $this->assertDatabaseHas('event_favorites', [
            'fav_event_id' => $event->id,
            'fav_user_id' => $user->id,
        ]);
    }
}