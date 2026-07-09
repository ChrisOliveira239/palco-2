<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventSessionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_at' => $this->ses_start_at,
            'end_at' => $this->ses_end_at,
            'pricing_type' => $this->ses_pricing_type->value,
            'price' => $this->ses_price,
            'capacity' => $this->ses_capacity,
        ];
    }
}