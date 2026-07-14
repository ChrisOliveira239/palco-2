<?php

namespace Tests\Unit\Actions\EventSession;

use App\Actions\EventSession\ListEventSessionsForAdmin;
use App\Models\Event;
use App\Models\EventSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListEventSessionsForAdminTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_returns_past_and_upcoming_sessions_of_event(): void
    {
        $event = Event::factory()->create();
        $upcoming = EventSession::factory()->for($event, 'event')->create();
        $past = EventSession::factory()->for($event, 'event')->past()->create();
        EventSession::factory()->create(); // outro evento

        $result = (new ListEventSessionsForAdmin())->handle($event);

        $this->assertCount(2, $result);
        $this->assertTrue($result->contains('id', $upcoming->id));
        $this->assertTrue($result->contains('id', $past->id));
    }

    public function test_handle_orders_sessions_by_start_at_ascending(): void
    {
        $event = Event::factory()->create();
        $later = EventSession::factory()->for($event, 'event')->create(['ses_start_at' => now()->addDays(10)]);
        $sooner = EventSession::factory()->for($event, 'event')->create(['ses_start_at' => now()->addDays(2)]);

        $result = (new ListEventSessionsForAdmin())->handle($event);

        $this->assertSame($sooner->id, $result->first()->id);
        $this->assertSame($later->id, $result->last()->id);
    }
}
