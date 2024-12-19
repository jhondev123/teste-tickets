<?php

namespace Api\V1\Tickets;

use App\Models\Ticket;
use Tests\ApiTestCase;

class GetTicketByIdTest extends ApiTestCase
{
    public function test_get_ticket_by_id():void
    {
        $ticket = Ticket::factory()->create();
        $response = $this->get(route('tickets.show', ['ticket' => $ticket->id]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "status",
            "data"
        ]);
    }

    public function test_get_ticket_by_id_not_found():void
    {
        $response = $this->get(route('tickets.show', ['ticket' => 999]));
        $response->assertStatus(404);
        $response->assertJsonStructure([
            "message",
            "status",
            "data"
        ]);
    }

}
