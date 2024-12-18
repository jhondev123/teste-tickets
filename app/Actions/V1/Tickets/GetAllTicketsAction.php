<?php

namespace App\Actions\V1\Tickets;

use App\Models\Ticket;

class GetAllTicketsAction
{
    public function __construct(private Ticket $ticket)
    {
    }

    public function execute()
    {
        return $this->ticket->all();

    }

}
