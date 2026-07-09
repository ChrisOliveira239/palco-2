<?php

namespace App\Http\Controllers;

use App\Actions\Event\CreateEvent;
use App\Actions\Event\DeleteEvent;
use App\Actions\Event\ListEvents;
use App\Actions\Event\UpdateEvent;
use App\Http\Requests\Event\DestroyEventRequest;
use App\Http\Requests\Event\IndexEventRequest;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;

class EventController extends Controller
{
    public function index(IndexEventRequest $request, ListEvents $listEvents)
    {
        $events = $listEvents->handle($request->validated('city_ids'));

        return EventResource::collection($events);
    }

    public function store(StoreEventRequest $request, CreateEvent $createEvent)
    {
        $event = $createEvent->handle($request->validated(), $request->user());

        return new EventResource($event->load('city'));
    }

    public function update(UpdateEventRequest $request, UpdateEvent $updateEvent, Event $event)
    {
        $event = $updateEvent->handle($event, $request->validated());

        return new EventResource($event->load('city'));
    }

    public function destroy(DestroyEventRequest $request, DeleteEvent $deleteEvent, Event $event)
    {
        $deleteEvent->handle($event);

        return response()->noContent();
    }
}