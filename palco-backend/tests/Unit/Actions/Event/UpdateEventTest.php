<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\UpdateEvent;
use App\Models\City;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_updates_event_fields(): void
    {
        $event = Event::factory()->create(['eve_title' => 'Título antigo']);
        $city = City::factory()->create();

        $updated = (new UpdateEvent())->handle($event, [
            'title' => 'Título novo',
            'synopsis' => null,
            'type' => 'oficina',
            'venue_name' => 'Novo local',
            'city_id' => $city->id,
            'ticket_url' => null,
        ]);

        $this->assertSame('Título novo', $updated->eve_title);
        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_title' => 'Título novo']);
    }
}