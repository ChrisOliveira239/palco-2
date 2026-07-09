<?php

namespace App\Actions\Ticket;

use App\Models\EventSession;
use Illuminate\Database\Eloquent\Collection;

class ListTickets
{
    public function handle(EventSession $session): Collection
    {
        return $session->tickets()->orderBy('ing_holder_name')->get();
    }
}