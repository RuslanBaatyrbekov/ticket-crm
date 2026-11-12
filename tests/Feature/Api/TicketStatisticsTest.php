<?php

namespace Tests\Feature\Api;

use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class TicketStatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_counts_tickets_for_today_week_and_month(): void
    {
        // Wednesday, so "this week" and "this month" are unambiguous.
        Carbon::setTestNow('2025-12-10 12:00:00');

        Ticket::factory()->count(2)->create(['created_at' => now()]);
        Ticket::factory()->create(['created_at' => now()->subDays(1)]);   // same week
        Ticket::factory()->create(['created_at' => now()->subDays(8)]);   // same month, previous week
        Ticket::factory()->create(['created_at' => now()->subMonths(2)]); // outside all ranges

        $this->getJson('/api/tickets/statistics')
            ->assertOk()
            ->assertExactJson([
                'data' => [
                    'total_today' => 2,
                    'total_week' => 3,
                    'total_month' => 4,
                ],
            ]);
    }
}
