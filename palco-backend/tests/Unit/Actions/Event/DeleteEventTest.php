<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\DeleteEvent;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_deactivates_event(): void
    {
        $event = Event::factory()->create();

        (new DeleteEvent())->handle($event);

        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_active' => false]);
    }

    public function test_handle_deactivates_event_sessions(): void
    {
        $event = Event::factory()->create();
        $session = $event->sessions()->create([
            'ses_start_at' => now()->addDays(3),
            'ses_pricing_type' => 'free',
        ]);

        (new DeleteEvent())->handle($event);

        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_active' => false]);
    }
}