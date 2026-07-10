<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Event\ListEventsForAdmin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Event\IndexEventRequest;
use App\Http\Resources\EventResource;

class EventController extends Controller
{
    public function index(IndexEventRequest $request, ListEventsForAdmin $listEventsForAdmin)
    {
        $events = $listEventsForAdmin->handle(
            $request->validated('search'),
            $request->validated('city_id'),
            $request->validated('status', 'active'),
        );

        return EventResource::collection($events);
    }
}