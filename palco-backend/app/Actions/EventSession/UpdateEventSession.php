<?php

namespace App\Actions\EventSession;

use App\Models\EventSession;

class UpdateEventSession
{
    public function handle(EventSession $eventSession, array $data): EventSession
    {
        $eventSession->update([
            'ses_start_at' => $data['start_at'],
            'ses_end_at' => $data['end_at'] ?? null,
            'ses_pricing_type' => $data['pricing_type'],
            'ses_price' => $data['price'] ?? null,
            'ses_capacity' => $data['capacity'] ?? null,
        ]);

        return $eventSession;
    }
}