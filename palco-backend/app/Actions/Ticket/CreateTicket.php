<?php

namespace App\Actions\Ticket;

use App\Models\EventSession;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CreateTicket
{
    public function handle(EventSession $session, User $user, ?string $document): Ticket
    {
        return DB::transaction(function () use ($session, $user, $document) {
            $lockedSession = EventSession::query()->lockForUpdate()->findOrFail($session->id);

            if (! $lockedSession->hasAvailableCapacity()) {
                throw ValidationException::withMessages(['session' => 'Sessão lotada.']);
            }

            return $lockedSession->tickets()->create([
                'ing_hash_code' => Str::random(40),
                'ing_holder_name' => $user->name,
                'ing_holder_document' => $document,
                'ing_holder_email' => $user->email,
                'ing_user_id' => $user->id,
                'ing_purchased_at' => now(),
                'ing_walk_in' => false,
            ]);
        });
    }
}
