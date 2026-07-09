<?php

namespace App\Actions\Event;

use App\Models\Event;

class UpdateEvent
{
    public function handle(Event $event, array $data): Event
    {
        $event->update([
            'eve_title' => $data['title'],
            'eve_synopsis' => $data['synopsis'] ?? null,
            'eve_type' => $data['type'],
            'eve_venue_name' => $data['venue_name'],
            'eve_city_id' => $data['city_id'],
            'eve_ticket_url' => $data['ticket_url'] ?? null,
        ]);

        return $event;
    }
}