<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->eve_title,
            'synopsis' => $this->eve_synopsis,
            'type' => $this->eve_type,
            'venue_name' => $this->eve_venue_name,
            'city' => new CityResource($this->whenLoaded('city')),
            'next_session_at' => $this->next_session_at,
            'ticket_url' => $this->eve_ticket_url,
            'poster_path' => $this->eve_poster_path,
            'active' => $this->eve_active,
            'sessions_count' => $this->sessions_count,
        ];
    }
}