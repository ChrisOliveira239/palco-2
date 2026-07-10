<?php

namespace Tests\Feature\Admin\Events;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_deletes_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/admin/events/{$event->id}");

        $response->assertNoContent();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_active' => false]);
    }

    public function test_non_admin_cannot_delete_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/admin/events/{$event->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_active' => true]);
    }

    public function test_deleting_already_deleted_event_is_idempotent(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create();
        Sanctum::actingAs($admin);

        $this->deleteJson("/api/admin/events/{$event->id}")->assertNoContent();
        $this->deleteJson("/api/admin/events/{$event->id}")->assertNoContent();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_active' => false]);
    }
}
