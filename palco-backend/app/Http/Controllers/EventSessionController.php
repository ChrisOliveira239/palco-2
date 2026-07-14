<?php

namespace App\Http\Controllers;

use App\Actions\EventSession\ListEventSessions;
use App\Http\Resources\EventSessionResource;
use App\Models\Event;

class EventSessionController extends Controller
{
    public function index(Event $event, ListEventSessions $listEventSessions)
    {
        $sessions = $listEventSessions->handle($event);

        return EventSessionResource::collection($sessions);
    }
}
