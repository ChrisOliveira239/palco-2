<?php

namespace App\Actions\EventSession;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class ListEventSessions
{
    public function handle(Event $event): Collection
    {
        return $event->sessions()
            ->where('ses_start_at', '>=', now())
            ->orderBy('ses_start_at')
            ->get();
    }
}
