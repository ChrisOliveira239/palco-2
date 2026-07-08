<?php

namespace App\Http\Controllers;

use App\Actions\Event\ListEvents;
use App\Http\Requests\Event\IndexEventRequest;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    public function index(IndexEventRequest $request, ListEvents $listEvents)
    {
        $events = $listEvents->handle($request->validated('city_ids'));

        return EventResource::collection($events);
    }
}
