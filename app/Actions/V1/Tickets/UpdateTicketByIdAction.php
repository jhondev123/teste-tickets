<?php

namespace App\Actions\V1\Tickets;

use App\Http\Requests\Api\V1\Tickets\UpdateTicketRequest;
use App\Models\Ticket;

class UpdateTicketByIdAction
{
    public function __construct(private Ticket $ticket)
    {

    }

    /**
     * @param UpdateTicketRequest $request
     * @param string $id
     * @return Ticket
     * Atualiza um ticket pegando os dados da requisição já validados
     *
     */
    public function execute(UpdateTicketRequest $request, string $id): Ticket
    {
        $dataValidated = $request->validated();
        $ticket = $this->ticket->find($id);
        if(!$ticket) {
            throw new \DomainException('Ticket não encontrado', 404);
        }
        $ticket->update($dataValidated);
        return $ticket;

    }

}
