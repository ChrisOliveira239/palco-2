<?php

namespace App\Actions\Event;

use App\Models\Event;

class RestoreEvent
{
    public function handle(Event $event): Event
    {
        $event->restore();

        return $event;
    }
}