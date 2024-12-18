<?php

namespace App\Actions\V1\Tickets;

use App\Models\Ticket;

class GetTicketByIdAction
{
    public function __construct(private Ticket $ticket)
    {

    }



    public function execute(string $ticketId):Ticket|null
    {
        return $this->ticket->find($ticketId);
    }

}
