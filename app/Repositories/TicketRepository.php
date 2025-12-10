<?php

namespace App\Repositories;

use App\Contracts\TicketRepositoryInterface;
use App\Models\Ticket;
use Illuminate\Support\Carbon;

class TicketRepository implements TicketRepositoryInterface
{
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    public function getStatistics(): array
    {
        return [
            'total_today' => Ticket::createdBetween(
                Carbon::now()->startOfDay(),
                Carbon::now()->endOfDay()
            )->count(),

            'total_week' => Ticket::createdBetween(
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            )->count(),

            'total_month' => Ticket::createdBetween(
                Carbon::now()->startOfMonth(),
                Carbon::now()->endOfMonth()
            )->count(),
        ];
    }
}
