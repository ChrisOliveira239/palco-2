<?php

namespace App\Http\Controllers\Web;

use App\Actions\Event\ListEvents;
use App\Http\Controllers\Controller;
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