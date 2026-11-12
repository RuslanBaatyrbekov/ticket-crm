<?php

namespace Tests\Feature\Api;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CreateTicketTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'name' => 'John Doe',
            'phone' => '+4915112345678',
            'email' => 'john@example.com',
            'subject' => 'Login issue',
            'message' => 'I cannot log in since yesterday evening.',
        ], $overrides);
    }

    public function test_it_creates_a_ticket_and_a_customer(): void
    {
        $response = $this->postJson('/api/tickets', $this->payload());

        $response->assertCreated()
            ->assertJsonPath('data.subject', 'Login issue')
            ->assertJsonPath('data.status', TicketStatus::NEW->value)
            ->assertJsonPath('data.customer.phone', '+4915112345678');

        $this->assertDatabaseHas('customers', ['phone' => '+4915112345678', 'email' => 'john@example.com']);
        $this->assertDatabaseHas('tickets', ['subject' => 'Login issue', 'status' => 'new']);
    }

    public function test_it_reuses_an_existing_customer_with_the_same_phone(): void
    {
        $customer = Customer::factory()->create(['phone' => '+4915112345678']);

        $this->postJson('/api/tickets', $this->payload())->assertCreated();

        $this->assertDatabaseCount('customers', 1);
        $this->assertSame(1, $customer->tickets()->count());
    }

    public function test_it_stores_an_attached_file(): void
    {
        Storage::fake('public');
        config(['media-library.disk_name' => 'public']);

        $response = $this->post('/api/tickets', $this->payload([
            'file' => UploadedFile::fake()->create('screenshot.pdf', 120, 'application/pdf'),
        ]), ['Accept' => 'application/json']);

        $response->assertCreated()->assertJsonPath('data.files.0.name', 'screenshot.pdf');
        $this->assertCount(1, Ticket::first()->getMedia('tickets'));
    }

    public function test_it_validates_the_payload(): void
    {
        $this->postJson('/api/tickets', [
            'phone' => 'not-a-phone',
            'email' => 'nope',
            'message' => 'short',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'phone', 'email', 'subject', 'message']);

        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_one_customer_can_create_only_one_ticket_per_day(): void
    {
        $this->postJson('/api/tickets', $this->payload())->assertCreated();

        $this->postJson('/api/tickets', $this->payload(['subject' => 'Second try']))
            ->assertStatus(429);

        $this->assertDatabaseCount('tickets', 1);
    }
}
