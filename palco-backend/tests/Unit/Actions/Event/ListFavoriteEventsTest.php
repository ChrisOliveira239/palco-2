<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\ListFavoriteEvents;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListFavoriteEventsTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_returns_only_favorite_events_of_given_user(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $event = Event::factory()->create();
        $otherEvent = Event::factory()->create();

        $event->favoritedBy()->attach($user->id);
        $otherEvent->favoritedBy()->attach($otherUser->id);

        $result = (new ListFavoriteEvents())->handle($user);

        $this->assertCount(1, $result);
        $this->assertSame($event->id, $result->first()->id);
    }
}