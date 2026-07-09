<?php

namespace Tests\Feature\Tickets;

use App\Models\EventSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreManualTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_creates_manual_ticket(): void
    {
        $admin = User::factory()->admin()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets/manual", [
            'holder_name' => 'Fulano de Tal',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('tickets', [
            'ing_session_id' => $session->id,
            'ing_holder_name' => 'Fulano de Tal',
            'ing_user_id' => null,
            'ing_walk_in' => true,
        ]);
    }

    public function test_non_admin_cannot_create_manual_ticket(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets/manual", [
            'holder_name' => 'Fulano de Tal',
        ]);

        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_create_manual_ticket(): void
    {
        $session = EventSession::factory()->create();

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets/manual", [
            'holder_name' => 'Fulano de Tal',
        ]);

        $response->assertStatus(401);
    }

    public function test_manual_ticket_requires_holder_name(): void
    {
        $admin = User::factory()->admin()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets/manual", []);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['holder_name']);
    }

    public function test_creating_manual_ticket_for_full_session_fails(): void
    {
        $admin = User::factory()->admin()->create();
        $session = EventSession::factory()->create(['ses_capacity' => 1]);
        $session->tickets()->create([
            'ing_hash_code' => 'hash-1',
            'ing_holder_name' => 'Fulano',
        ]);
        Sanctum::actingAs($admin);

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets/manual", [
            'holder_name' => 'Beltrano',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['session']);
    }
}