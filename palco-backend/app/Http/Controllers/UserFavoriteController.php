<?php

namespace App\Http\Controllers;

use App\Actions\Event\ListFavoriteEvents;
use App\Http\Resources\EventResource;
use Illuminate\Http\Request;

class UserFavoriteController extends Controller
{
    public function index(Request $request, ListFavoriteEvents $listFavoriteEvents)
    {
        $events = $listFavoriteEvents->handle($request->user());

        return EventResource::collection($events);
    }
}