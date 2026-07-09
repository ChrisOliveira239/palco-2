<?php

namespace App\Actions\Ticket;

use App\Models\EventSession;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateManualTicket
{
    public function handle(EventSession $session, array $data): Ticket
    {
        return DB::transaction(function () use ($session, $data) {
            $lockedSession = EventSession::query()->lockForUpdate()->findOrFail($session->id);

            if (! $lockedSession->hasAvailableCapacity()) {
                throw ValidationException::withMessages(['session' => 'Sessão lotada.']);
            }

            return $lockedSession->tickets()->create([
                'ing_hash_code' => Str::random(40),
                'ing_holder_name' => $data['holder_name'],
                'ing_holder_document' => $data['holder_document'] ?? null,
                'ing_holder_email' => $data['holder_email'] ?? null,
                'ing_user_id' => null,
                'ing_purchased_at' => now(),
                'ing_walk_in' => true,
            ]);
        });
    }
}