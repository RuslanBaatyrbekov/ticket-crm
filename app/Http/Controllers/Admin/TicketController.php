<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Enums\TicketStatus;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::with('customer')
        ->latest()
        ->filter($request->only(['status', 'date', 'search']))
            ->paginate(20)
            ->withQueryString();

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'statuses' => TicketStatus::cases()
        ]);
    }

    public function show(Ticket $ticket)
    {
        return view('admin.tickets.show', [
            'ticket' => $ticket->load(['customer', 'media']),
            'statuses' => TicketStatus::cases()
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {

        $request->validate(['status' => 'required|string']);

        $ticket->update([
            'status' => $request->status,
            'manager_reply_at' => now(),
        ]);

        return back()->with('success', 'Статус успешно обновлен!');
    }
}
