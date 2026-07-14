<?php

namespace App\Http\Controllers\Admin;

use App\Actions\EventSession\CreateEventSession;
use App\Actions\EventSession\DeleteEventSession;
use App\Actions\EventSession\ListEventSessionsForAdmin;
use App\Actions\EventSession\UpdateEventSession;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EventSession\DestroyEventSessionRequest;
use App\Http\Requests\Admin\EventSession\IndexEventSessionRequest;
use App\Http\Requests\Admin\EventSession\StoreEventSessionRequest;
use App\Http\Requests\Admin\EventSession\UpdateEventSessionRequest;
use App\Http\Resources\EventSessionResource;
use App\Models\Event;
use App\Models\EventSession;

class EventSessionController extends Controller
{
    public function index(IndexEventSessionRequest $request, int $event, ListEventSessionsForAdmin $listEventSessionsForAdmin)
    {
        $sessions = $listEventSessionsForAdmin->handle($this->resolveEvent($event));

        return EventSessionResource::collection($sessions);
    }

    public function store(StoreEventSessionRequest $request, int $event, CreateEventSession $createEventSession)
    {
        $session = $createEventSession->handle($this->resolveEvent($event), $request->validated());

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

    private function resolveEvent(int $id): Event
    {
        return Event::withInactive()->findOrFail($id);
    }
}
