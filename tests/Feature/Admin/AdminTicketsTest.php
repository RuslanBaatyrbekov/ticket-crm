<?php

namespace Tests\Feature\Admin;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminTicketsTest extends TestCase
{
    use RefreshDatabase;

    private function userWithRole(?string $role): User
    {
        $user = User::factory()->create();

        if ($role) {
            Role::findOrCreate($role);
            $user->assignRole($role);
        }

        return $user;
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/admin/tickets')->assertRedirect('/login');
    }

    public function test_users_without_a_staff_role_are_forbidden(): void
    {
        $this->actingAs($this->userWithRole(null))
            ->get('/admin/tickets')
            ->assertForbidden();
    }

    public static function staffRoles(): array
    {
        return [['admin'], ['manager']];
    }

    #[DataProvider('staffRoles')]
    public function test_staff_can_see_the_ticket_list(string $role): void
    {
        $ticket = Ticket::factory()->create(['subject' => 'Printer is on fire']);

        $this->actingAs($this->userWithRole($role))
            ->get('/admin/tickets')
            ->assertOk()
            ->assertSee('Printer is on fire')
            ->assertSee($ticket->customer->name);
    }

    public function test_the_list_can_be_filtered_by_status_and_customer(): void
    {
        $alice = Customer::factory()->create(['email' => 'alice@example.com']);
        Ticket::factory()->for($alice)->create(['subject' => 'Alice new', 'status' => TicketStatus::NEW]);
        Ticket::factory()->for($alice)->create(['subject' => 'Alice done', 'status' => TicketStatus::PROCESSED]);
        Ticket::factory()->create(['subject' => 'Someone else new', 'status' => TicketStatus::NEW]);

        $this->actingAs($this->userWithRole('manager'))
            ->get('/admin/tickets?status=new&search=alice@')
            ->assertOk()
            ->assertSee('Alice new')
            ->assertDontSee('Alice done')
            ->assertDontSee('Someone else new');
    }

    public function test_a_manager_can_change_the_ticket_status(): void
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatus::NEW]);

        $this->actingAs($this->userWithRole('manager'))
            ->from("/admin/tickets/{$ticket->id}")
            ->patch("/admin/tickets/{$ticket->id}", ['status' => 'in_progress'])
            ->assertRedirect("/admin/tickets/{$ticket->id}");

        $ticket->refresh();
        $this->assertSame(TicketStatus::IN_PROGRESS, $ticket->status);
        $this->assertNotNull($ticket->manager_reply_at);
    }

    public function test_an_unknown_status_is_rejected(): void
    {
        $ticket = Ticket::factory()->create(['status' => TicketStatus::NEW]);

        $this->actingAs($this->userWithRole('manager'))
            ->patch("/admin/tickets/{$ticket->id}", ['status' => 'deleted'])
            ->assertSessionHasErrors('status');

        $this->assertSame(TicketStatus::NEW, $ticket->fresh()->status);
    }
}
