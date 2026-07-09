<?php

namespace Tests\Unit\Actions\EventSession;

use App\Actions\EventSession\UpdateEventSession;
use App\Models\EventSession;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateEventSessionTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_updates_session_fields(): void
    {
        $session = EventSession::factory()->create(['ses_capacity' => 10]);

        $updated = (new UpdateEventSession())->handle($session, [
            'start_at' => now()->addDays(3),
            'end_at' => null,
            'pricing_type' => 'fixed',
            'price' => 25,
            'capacity' => 80,
        ]);

        $this->assertSame(80, $updated->ses_capacity);
        $this->assertSame('fixed', $updated->ses_pricing_type->value);
        $this->assertDatabaseHas('event_sessions', ['id' => $session->id, 'ses_capacity' => 80]);
    }
}