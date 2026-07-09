<?php

namespace App\Actions\Event;

use App\Models\Event;
use App\Models\User;

class CreateEvent
{
    public function handle(array $data, User $creator): Event
    {
        return Event::create([
            'eve_title' => $data['title'],
            'eve_synopsis' => $data['synopsis'] ?? null,
            'eve_type' => $data['type'],
            'eve_venue_name' => $data['venue_name'],
            'eve_city_id' => $data['city_id'],
            'eve_ticket_url' => $data['ticket_url'] ?? null,
            'eve_created_by_id' => $creator->id,
        ]);
    }
}
