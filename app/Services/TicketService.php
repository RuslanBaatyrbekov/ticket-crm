<?php

namespace App\Services;

use App\Contracts\TicketRepositoryInterface;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        protected TicketRepositoryInterface $ticketRepository
    ) {}

    /**
     * Creates a ticket, finding or creating the customer by phone number.
     */
    public function createTicket(array $data): Ticket
    {
        return DB::transaction(function () use ($data) {
            $customer = Customer::firstOrCreate(
                ['phone' => $data['phone']],
                [
                    'name' => $data['name'],
                    'email' => $data['email'] ?? null,
                ]
            );

            $ticket = $this->ticketRepository->create([
                'customer_id' => $customer->id,
                'subject' => $data['subject'],
                'content' => $data['message'],
            ]);

            if (isset($data['file'])) {
                $ticket->addMedia($data['file'])
                    ->toMediaCollection('tickets');
            }

            return $ticket->refresh();
        });
    }

    /**
     * Ticket counts for today, the current week and the current month.
     */
    public function getStatistics(): array
    {
        return $this->ticketRepository->getStatistics();
    }
}
