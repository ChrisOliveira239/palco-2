<?php

namespace Tests\Feature\EventSessions;

use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_lists_upcoming_sessions_of_event(): void
    {
        $event = Event::factory()->create();
        $session = EventSession::factory()->for($event, 'event')->create();

        $response = $this->getJson("/api/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $session->id);
    }

    public function test_excludes_past_sessions(): void
    {
        $event = Event::factory()->create();
        EventSession::factory()->for($event, 'event')->past()->create();

        $response = $this->getJson("/api/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_excludes_sessions_of_other_events(): void
    {
        $event = Event::factory()->create();
        $otherEvent = Event::factory()->create();
        EventSession::factory()->for($otherEvent, 'event')->create();

        $response = $this->getJson("/api/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_orders_sessions_by_start_at_ascending(): void
    {
        $event = Event::factory()->create();
        $later = EventSession::factory()->for($event, 'event')->create(['ses_start_at' => now()->addDays(10)]);
        $sooner = EventSession::factory()->for($event, 'event')->create(['ses_start_at' => now()->addDays(2)]);

        $response = $this->getJson("/api/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonPath('data.0.id', $sooner->id);
        $response->assertJsonPath('data.1.id', $later->id);
    }
}