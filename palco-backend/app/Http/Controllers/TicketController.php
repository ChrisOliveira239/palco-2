<?php

namespace App\Http\Controllers;

use App\Actions\Ticket\CreateManualTicket;
use App\Actions\Ticket\CreateTicket;
use App\Actions\Ticket\ListTickets;
use App\Http\Requests\Ticket\IndexTicketRequest;
use App\Http\Requests\Ticket\StoreManualTicketRequest;
use App\Http\Requests\Ticket\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Models\EventSession;

class TicketController extends Controller
{
    public function index(IndexTicketRequest $request, EventSession $eventSession, ListTickets $listTickets)
    {
        $tickets = $listTickets->handle($eventSession);

        return TicketResource::collection($tickets);
    }

    public function store(StoreTicketRequest $request, EventSession $eventSession, CreateTicket $createTicket)
    {
        $ticket = $createTicket->handle($eventSession, $request->user(), $request->validated('holder_document'));

        return new TicketResource($ticket);
    }

    public function storeManual(StoreManualTicketRequest $request, EventSession $eventSession, CreateManualTicket $createManualTicket)
    {
        $ticket = $createManualTicket->handle($eventSession, $request->validated());

        return new TicketResource($ticket);
    }
}
