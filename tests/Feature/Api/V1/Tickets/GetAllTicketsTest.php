<?php

namespace Api\V1\Tickets;

use Tests\ApiTestCase;

class GetAllTicketsTest extends ApiTestCase
{
    public function test_get_all_tickets()
    {
        $response = $this->get(route('tickets.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            "message",
            "status",
            "data"
        ]);
    }

}
