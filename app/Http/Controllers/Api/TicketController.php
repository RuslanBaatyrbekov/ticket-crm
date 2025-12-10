<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    public function store(StoreTicketRequest $request): TicketResource
    {
        $ticket = $this->ticketService->createTicket($request->validated());

        return new TicketResource($ticket);
    }

    public function statistics(): JsonResponse
    {
        $stats = $this->ticketService->getStatistics();

        return response()->json([
            'data' => $stats
        ]);
    }
}
