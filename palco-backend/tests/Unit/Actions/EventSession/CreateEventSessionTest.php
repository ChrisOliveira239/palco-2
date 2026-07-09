<?php

namespace Tests\Unit\Actions\EventSession;

use App\Actions\EventSession\CreateEventSession;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEventSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_creates_session_attached_to_event(): void
    {
        $event = Event::factory()->create();
        $startAt = now()->addDays(5);

        $session = (new CreateEventSession())->handle($event, [
            'start_at' => $startAt,
            'end_at' => null,
            'pricing_type' => 'fixed',
            'price' => 40,
            'capacity' => 50,
        ]);

        $this->assertSame($event->id, $session->ses_event_id);
        $this->assertSame('fixed', $session->ses_pricing_type->value);
        $this->assertSame(50, $session->ses_capacity);
        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_event_id' => $event->id]);
    }
}