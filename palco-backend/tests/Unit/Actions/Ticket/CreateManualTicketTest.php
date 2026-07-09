<?php

namespace Tests\Unit\Actions\Ticket;

use App\Actions\Ticket\CreateManualTicket;
use App\Models\EventSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateManualTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_creates_walk_in_ticket(): void
    {
        $session = EventSession::factory()->create();

        $ticket = (new CreateManualTicket())->handle($session, ['holder_name' => 'Fulano de Tal']);

        $this->assertSame($session->id, $ticket->ing_session_id);
        $this->assertNull($ticket->ing_user_id);
        $this->assertTrue($ticket->ing_walk_in);
        $this->assertSame('Fulano de Tal', $ticket->ing_holder_name);
    }

    public function test_handle_throws_validation_exception_when_session_is_full(): void
    {
        $session = EventSession::factory()->create(['ses_capacity' => 1]);
        $session->tickets()->create([
            'ing_hash_code' => 'hash-1',
            'ing_holder_name' => 'Fulano',
        ]);

        $this->expectException(ValidationException::class);

        (new CreateManualTicket())->handle($session, ['holder_name' => 'Beltrano']);
    }
}