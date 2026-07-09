<?php

namespace App\Actions\EventSession;

use App\Models\Event;
use App\Models\EventSession;

class CreateEventSession
{
    public function handle(Event $event, array $data): EventSession
    {
        return $event->sessions()->create([
            'ses_start_at' => $data['start_at'],
            'ses_end_at' => $data['end_at'] ?? null,
            'ses_pricing_type' => $data['pricing_type'],
            'ses_price' => $data['price'] ?? null,
            'ses_capacity' => $data['capacity'] ?? null,
        ]);
    }
}