<?php

namespace App\Actions\Event;

use App\Models\Event;

class DeleteEvent
{
    public function handle(Event $event): void
    {
        $event->sessions()->each(fn ($session) => $session->delete());
        $event->delete();
    }
}