<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\AddEventFavorite;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AddEventFavoriteTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_attaches_user_to_event_favorites(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        (new AddEventFavorite())->handle($event, $user);

        $this->assertDatabaseHas('event_favorites', [
            'fav_event_id' => $event->id,
            'fav_user_id' => $user->id,
        ]);
    }

    public function test_handle_called_twice_does_not_duplicate(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();

        (new AddEventFavorite())->handle($event, $user);
        (new AddEventFavorite())->handle($event, $user);

        $this->assertDatabaseCount('event_favorites', 1);
    }
}