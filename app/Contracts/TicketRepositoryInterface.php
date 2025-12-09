<?php

namespace App\Contracts;

use App\Models\Ticket;

interface TicketRepositoryInterface
{
    public function create(array $data): Ticket;
}
