<?php

namespace App\Http\Controllers;

use App\Actions\Event\AddEventFavorite;
use App\Actions\Event\RemoveEventFavorite;
use App\Models\Event;
use Illuminate\Http\Request;

class EventFavoriteController extends Controller
{
    public function store(Request $request, Event $event, AddEventFavorite $addEventFavorite)
    {
        $addEventFavorite->handle($event, $request->user());

        return response()->noContent(201);
    }

    public function destroy(Request $request, Event $event, RemoveEventFavorite $removeEventFavorite)
    {
        $removeEventFavorite->handle($event, $request->user());

        return response()->noContent();
    }
}