<?php

namespace Tests\Feature\Tickets;

use App\Models\EventSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_lists_tickets_of_session(): void
    {
        $admin = User::factory()->admin()->create();
        $session = EventSession::factory()->create();
        $ticket = $session->tickets()->create([
            'ing_hash_code' => 'hash-1',
            'ing_holder_name' => 'Fulano',
        ]);
        Sanctum::actingAs($admin);

        $response = $this->getJson("/api/event-sessions/{$session->id}/tickets");

        $response->assertOk();
        $response->assertJsonCount(1, 'data');
        $response->assertJsonPath('data.0.id', $ticket->id);
    }

    public function test_non_admin_cannot_list_tickets(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create();
        Sanctum::actingAs($user);

        $response = $this->getJson("/api/event-sessions/{$session->id}/tickets");

        $response->assertStatus(403);
    }

    public function test_unauthenticated_cannot_list_tickets(): void
    {
        $session = EventSession::factory()->create();

        $response = $this->getJson("/api/event-sessions/{$session->id}/tickets");

        $response->assertStatus(401);
    }

    public function test_listing_tickets_of_nonexistent_session_returns_404(): void
    {
        $admin = User::factory()->admin()->create();
        Sanctum::actingAs($admin);

        $response = $this->getJson('/api/event-sessions/999999/tickets');

        $response->assertStatus(404);
    }
}