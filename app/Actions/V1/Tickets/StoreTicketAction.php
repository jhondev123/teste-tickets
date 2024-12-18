<?php

namespace App\Actions\V1\Tickets;

use App\Enums\TicketSituation;
use App\Http\Requests\Api\V1\Tickets\StoreTicketRequest;
use App\Models\Ticket;

class StoreTicketAction
{
    public function __construct(private Ticket $ticket)
    {

    }

    public function execute(StoreTicketRequest $request)
    {
        $dataValidated = $request->validated();
        if (!isset($dataValidated['situation'])) {
            $dataValidated['situation'] = TicketSituation::Active->value;
        }
        return $this->ticket->create($dataValidated);

    }

}
