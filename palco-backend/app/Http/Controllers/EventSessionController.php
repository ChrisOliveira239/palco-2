<?php

namespace App\Http\Controllers;

use App\Actions\EventSession\CreateEventSession;
use App\Actions\EventSession\DeleteEventSession;
use App\Actions\EventSession\ListEventSessions;
use App\Actions\EventSession\UpdateEventSession;
use App\Http\Requests\EventSession\DestroyEventSessionRequest;
use App\Http\Requests\EventSession\StoreEventSessionRequest;
use App\Http\Requests\EventSession\UpdateEventSessionRequest;
use App\Http\Resources\EventSessionResource;
use App\Models\Event;
use App\Models\EventSession;

class EventSessionController extends Controller
{
    public function index(Event $event, ListEventSessions $listEventSessions)
    {
        $sessions = $listEventSessions->handle($event);

        return EventSessionResource::collection($sessions);
    }

    public function store(StoreEventSessionRequest $request, Event $event, CreateEventSession $createEventSession)
    {
        $session = $createEventSession->handle($event, $request->validated());

        return new EventSessionResource($session);
    }

    public function update(UpdateEventSessionRequest $request, EventSession $eventSession, UpdateEventSession $updateEventSession)
    {
        $eventSession = $updateEventSession->handle($eventSession, $request->validated());

        return new EventSessionResource($eventSession);
    }

    public function destroy(DestroyEventSessionRequest $request, EventSession $eventSession, DeleteEventSession $deleteEventSession)
    {
        $deleteEventSession->handle($eventSession);

        return response()->noContent();
    }
}