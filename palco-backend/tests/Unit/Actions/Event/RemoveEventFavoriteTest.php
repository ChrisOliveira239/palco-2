<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\RemoveEventFavorite;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RemoveEventFavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_detaches_user_from_event_favorites(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        $event->favoritedBy()->attach($user->id);

        (new RemoveEventFavorite())->handle($event, $user);

        $this->assertDatabaseMissing('event_favorites', [
            'fav_event_id' => $event->id,
            'fav_user_id' => $user->id,
        ]);
    }

    public function test_handle_without_previous_favorite_does_not_error(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        (new RemoveEventFavorite())->handle($event, $user);

        $this->assertDatabaseCount('event_favorites', 0);
    }
}