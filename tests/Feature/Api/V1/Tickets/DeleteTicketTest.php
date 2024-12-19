<?php

namespace Api\V1\Tickets;

use App\Models\Ticket;
use Tests\ApiTestCase;

class DeleteTicketTest extends ApiTestCase
{
    public function test_delete_ticket()
    {
        $ticket = Ticket::factory()->create();
        $response = $this->delete(route('tickets.destroy', ['ticket' => $ticket->id]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "status",
            "data"
        ]);
    }

    public function test_delete_ticket_not_found()
    {
        $response = $this->delete(route('tickets.destroy', ['ticket' => 999]));
        $response->assertStatus(404);
        $response->assertJsonStructure([
            "message",
            "status",
            "errors"
        ]);
    }

}
