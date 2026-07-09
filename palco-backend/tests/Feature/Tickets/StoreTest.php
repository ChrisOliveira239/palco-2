<?php

namespace Tests\Feature\Tickets;

use App\Models\EventSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_purchases_ticket(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets", [
            'holder_document' => '12345678900',
        ]);

        $response->assertCreated();
        $this->assertDatabaseHas('tickets', [
            'ing_session_id' => $session->id,
            'ing_user_id' => $user->id,
            'ing_walk_in' => false,
        ]);
    }

    public function test_purchases_ticket_without_holder_document(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets");

        $response->assertCreated();
    }

    public function test_unauthenticated_cannot_purchase_ticket(): void
    {
        $session = EventSession::factory()->create();

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets");

        $response->assertStatus(401);
    }

    public function test_purchasing_ticket_for_full_session_fails(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create(['ses_capacity' => 1]);
        $session->tickets()->create([
            'ing_hash_code' => 'hash-1',
            'ing_holder_name' => 'Fulano',
        ]);
        Sanctum::actingAs($user);

        $response = $this->postJson("/api/event-sessions/{$session->id}/tickets");

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['session']);
    }

    public function test_purchasing_ticket_for_nonexistent_session_returns_404(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->postJson('/api/event-sessions/999999/tickets');

        $response->assertStatus(404);
    }
}