<?php

namespace App\Actions\Event;

use App\Models\Event;

class DeleteEvent
{
    public function handle(Event $event): void
    {
        $event->delete();
    }
}