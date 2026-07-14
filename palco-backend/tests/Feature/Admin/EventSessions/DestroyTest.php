<?php

namespace Tests\Feature\Admin\EventSessions;

use App\Models\EventSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_deletes_event_session(): void
    {
        $admin = User::factory()->admin()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/admin/event-sessions/{$session->id}");

        $response->assertNoContent();
        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_active' => false]);
    }

    public function test_non_admin_cannot_delete_event_session(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->deleteJson("/api/admin/event-sessions/{$session->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_active' => true]);
    }

    public function test_admin_deletes_event_session_with_tickets(): void
    {
        $admin = User::factory()->admin()->create();
        $session = EventSession::factory()->create();
        $session->tickets()->create([
            'ing_hash_code' => 'test-hash-code',
            'ing_holder_name' => 'Fulano de Tal',
        ]);
        Sanctum::actingAs($admin);

        $response = $this->deleteJson("/api/admin/event-sessions/{$session->id}");

        $response->assertNoContent();
        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_active' => false]);
    }

    public function test_deleting_already_deleted_event_session_returns_404(): void
    {
        $admin = User::factory()->admin()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($admin);

        $this->deleteJson("/api/admin/event-sessions/{$session->id}")->assertNoContent();
        $this->deleteJson("/api/admin/event-sessions/{$session->id}")->assertStatus(404);
    }

    public function test_unauthenticated_cannot_delete_event_session(): void
    {
        $session = EventSession::factory()->create();

        $response = $this->deleteJson("/api/admin/event-sessions/{$session->id}");

        $response->assertStatus(401);
    }
}
