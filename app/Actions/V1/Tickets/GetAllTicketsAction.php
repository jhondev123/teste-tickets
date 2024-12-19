<?php

namespace App\Actions\V1\Tickets;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;

class GetAllTicketsAction
{
    public function __construct(private Ticket $ticket)
    {
    }

    /**
     * @return Collection
     * Busca todos os tickets
     */
    public function execute():Collection
    {
        return $this->ticket->all();

    }

}
