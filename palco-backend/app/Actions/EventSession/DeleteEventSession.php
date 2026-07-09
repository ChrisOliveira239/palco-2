<?php

namespace App\Actions\EventSession;

use App\Models\EventSession;

class DeleteEventSession
{
    public function handle(EventSession $eventSession): void
    {
        $eventSession->delete();
    }
}
