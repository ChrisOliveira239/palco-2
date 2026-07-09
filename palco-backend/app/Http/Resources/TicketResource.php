<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'hash_code' => $this->ing_hash_code,
            'holder_name' => $this->ing_holder_name,
            'holder_document' => $this->ing_holder_document,
            'holder_email' => $this->ing_holder_email,
            'user_id' => $this->ing_user_id,
            'walk_in' => $this->ing_walk_in,
            'purchased_at' => $this->ing_purchased_at,
        ];
    }
}
