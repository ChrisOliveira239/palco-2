<?php

namespace Tests\Feature\Admin\EventSessions;

use App\Models\Event;
use App\Models\EventSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_lists_sessions_of_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        $session = EventSession::factory()->for($event, 'event')->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/admin/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $session->id);
    }

    public function test_admin_lists_past_sessions_too(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        $past = EventSession::factory()->for($event, 'event')->past()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/admin/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $past->id);
    }

    public function test_admin_lists_sessions_of_inactive_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create(['eve_active' => false]);
        $session = EventSession::factory()->for($event, 'event')->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/admin/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $session->id);
    }

    public function test_excludes_sessions_of_other_events(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        $otherEvent = Event::factory()->create();
        EventSession::factory()->for($otherEvent, 'event')->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/admin/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonCount(0, 'data');
    }

    public function test_orders_sessions_by_start_at_ascending(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        $later = EventSession::factory()->for($event, 'event')->create(['ses_start_at' => now()->addDays(10)]);
        $sooner = EventSession::factory()->for($event, 'event')->create(['ses_start_at' => now()->addDays(2)]);
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/admin/events/{$event->id}/sessions");

        $response->assertOk();
        $response->assertJsonPath('data.0.id', $sooner->id);
        $response->assertJsonPath('data.1.id', $later->id);
    }

    public function test_non_admin_cannot_list_event_sessions(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/admin/events/{$event->id}/sessions");

        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_list_event_sessions(): void
    {
        $event = Event::factory()->create();

        $response = $this->getJson("/api/admin/events/{$event->id}/sessions");

        $response->assertStatus(401);
    }
}
