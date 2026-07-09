<?php

namespace Tests\Unit\Actions\Event;

use App\Actions\Event\CreateEvent;
use App\Models\City;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_creates_event_with_creator(): void
    {
        $admin = User::factory()->admin()->create();
        $city = City::factory()->create();

        $event = (new CreateEvent())->handle([
            'title' => 'Show de Rock',
            'synopsis' => 'Uma noite de rock.',
            'type' => 'show',
            'venue_name' => 'Arena X',
            'city_id' => $city->id,
            'ticket_url' => null,
        ], $admin);

        $this->assertSame('Show de Rock', $event->eve_title);
        $this->assertSame($city->id, $event->eve_city_id);
        $this->assertSame($admin->id, $event->eve_created_by_id);
        $this->assertDatabaseHas('events', ['id' => $event->id]);
    }
}