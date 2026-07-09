<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\DeleteEvent;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_deletes_event(): void
    {
        $event = Event::factory()->create();

        (new DeleteEvent())->handle($event);

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}