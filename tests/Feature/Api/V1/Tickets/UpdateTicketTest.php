<?php

namespace Api\V1\Tickets;

use App\Models\Ticket;
use Tests\ApiTestCase;

class UpdateTicketTest extends ApiTestCase
{
    public function test_update_ticket()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->put(route('tickets.update', $ticket->id), [
            'quantity' => 10,
            'situation' => 'I'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'message',
                'status'
            ]);
    }

    public function test_update_ticket_with_quantity_invalid()
    {
        $ticket = Ticket::factory()->create();

        $response = $this->put(route('tickets.update', $ticket->id), [
            'quantity' => 'invalid'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'quantity'
                ]
            ]);
    }

    public function test_update_ticket_with_quantity_less_than_1(): void
    {
        $ticket = Ticket::factory()->create();
        $response = $this->put(route('tickets.update', $ticket->id), [
            'quantity' => 0
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'quantity'
                ]
            ]);
    }

    public function test_update_ticket_with_invalid_situation(): void
    {
        $ticket = Ticket::factory()->create();
        $response = $this->put(route('tickets.update', $ticket->id), [
            'quantity' => 10,
            'situation' => 'invalid'
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors' => [
                    'situation'
                ]
            ]);

    }


}
