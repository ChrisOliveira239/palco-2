<?php

namespace Tests\Unit\Actions\Ticket;

use App\Actions\Ticket\ListTickets;
use App\Models\EventSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListTicketsTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_returns_only_tickets_of_given_session_ordered_by_holder_name(): void
    {
        $session = EventSession::factory()->create();
        $otherSession = EventSession::factory()->create();

        $session->tickets()->create(['ing_hash_code' => 'hash-1', 'ing_holder_name' => 'Zeca']);
        $session->tickets()->create(['ing_hash_code' => 'hash-2', 'ing_holder_name' => 'Ana']);
        $otherSession->tickets()->create(['ing_hash_code' => 'hash-3', 'ing_holder_name' => 'Beto']);

        $result = (new ListTickets())->handle($session);

        $this->assertCount(2, $result);
        $this->assertSame('Ana', $result->first()->ing_holder_name);
        $this->assertSame('Zeca', $result->last()->ing_holder_name);
    }
}