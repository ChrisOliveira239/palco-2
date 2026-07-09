<?php

namespace Tests\Unit\Actions\EventSession;

use App\Actions\EventSession\DeleteEventSession;
use App\Models\EventSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteEventSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_deactivates_session(): void
    {
        $session = EventSession::factory()->create();

        (new DeleteEventSession())->handle($session);

        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_active' => false]);
    }

    public function test_handle_does_not_fail_when_session_has_tickets(): void
    {
        $session = EventSession::factory()->create();
        $ticket = $session->tickets()->create([
            'ing_hash_code' => 'test-hash-code',
            'ing_holder_name' => 'Fulano de Tal',
        ]);

        (new DeleteEventSession())->handle($session);

        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_active' => false]);
        $this->assertDatabaseHas('tickets', ['id' => $ticket->id]);
    }
}