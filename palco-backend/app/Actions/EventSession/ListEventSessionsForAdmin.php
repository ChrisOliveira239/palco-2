<?php

namespace App\Actions\EventSession;

use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;

class ListEventSessionsForAdmin
{
    public function handle(Event $event): Collection
    {
        return $event->sessions()
            ->orderBy('ses_start_at')
            ->get();
    }
}
