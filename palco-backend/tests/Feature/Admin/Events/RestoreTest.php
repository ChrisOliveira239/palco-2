<?php

namespace Tests\Feature\Admin\Events;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RestoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_restores_event(): void
    {
        $admin = User::factory()->admin()->create();
        $event = Event::factory()->create(['eve_active' => false]);
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/admin/events/{$event->id}/restore");

        $response->assertOk();
        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_active' => true]);
    }

    public function test_non_admin_cannot_restore_event(): void
    {
        $user = User::factory()->create();
        $event = Event::factory()->create(['eve_active' => false]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/admin/events/{$event->id}/restore");

        $response->assertStatus(403);
        $this->assertDatabaseHas('events', ['id' => $event->id, 'eve_active' => false]);
    }

    public function test_restoring_nonexistent_event_returns_404(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson('/api/admin/events/99999/restore');

        $response->assertStatus(404);
    }
}