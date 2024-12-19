<?php

namespace App\Actions\V1\Tickets;

use App\Models\Ticket;

class DeleteTicketAction
{
    public function __construct(private Ticket $ticket)
    {

    }

    /**
     * @param string $ticketId
     * @return bool
     * Deleta um ticket pelo ID
     */
    public function execute(string $ticketId):bool
    {
        $ticket = $this->ticket->find($ticketId);
        if(!$ticket) {
            throw new \DomainException('Ticket not found', 404);
        }
        return $ticket->delete();

    }

}
