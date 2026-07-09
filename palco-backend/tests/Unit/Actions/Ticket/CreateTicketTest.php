<?php

namespace Tests\Unit\Actions\Ticket;

use App\Actions\Ticket\CreateTicket;
use App\Models\EventSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_creates_ticket_attached_to_session_and_user(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create();

        $ticket = (new CreateTicket())->handle($session, $user, '12345678900');

        $this->assertSame($session->id, $ticket->ing_session_id);
        $this->assertSame($user->id, $ticket->ing_user_id);
        $this->assertFalse($ticket->ing_walk_in);
        $this->assertSame('12345678900', $ticket->ing_holder_document);
    }

    public function test_handle_throws_validation_exception_when_session_is_full(): void
    {
        $user = User::factory()->create();
        $session = EventSession::factory()->create(['ses_capacity' => 1]);
        $session->tickets()->create([
            'ing_hash_code' => 'hash-1',
            'ing_holder_name' => 'Fulano',
        ]);

        $this->expectException(ValidationException::class);

        (new CreateTicket())->handle($session, $user, null);
    }
}