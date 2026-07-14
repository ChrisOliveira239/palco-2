<?php

namespace Tests\Feature\Admin\EventSessions;

use App\Models\EventSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_updates_event_session(): void
    {
        $admin = User::factory()->admin()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->putJson("/api/admin/event-sessions/{$session->id}", [
            'start_at' => now()->addDays(3)->toDateTimeString(),
            'end_at' => null,
            'pricing_type' => 'fixed',
            'price' => 30,
            'capacity' => 20,
        ]);

        $response->assertOk();
        $response->assertJsonPath('data.pricing_type', 'fixed');
        $response->assertJsonPath('data.capacity', 20);

        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_capacity' => 20]);
    }

    public function test_updating_nonexistent_event_session_returns_404(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $response = $this->putJson('/api/admin/event-sessions/999999', [
            'start_at' => now()->addDays(3)->toDateTimeString(),
            'pricing_type' => 'free',
        ]);

        $response->assertStatus(404);
    }

    public function test_non_admin_cannot_update_event_session(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->putJson("/api/admin/event-sessions/{$session->id}", [
            'start_at' => now()->addDays(3)->toDateTimeString(),
            'pricing_type' => 'free',
        ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_update_event_session(): void
    {
        $session = EventSession::factory()->create();

        $response = $this->putJson("/api/admin/event-sessions/{$session->id}", [
            'start_at' => now()->addDays(3)->toDateTimeString(),
            'pricing_type' => 'free',
        ]);

        $response->assertStatus(401);
    }
}
