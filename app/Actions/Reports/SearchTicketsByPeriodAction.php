<?php

namespace App\Actions\Reports;

use App\DTO\Reports\SearchTicketsByPeriodDTO;
use App\Models\Ticket;

class SearchTicketsByPeriodAction
{
    public static function execute(SearchTicketsByPeriodDTO $dto)
    {
        return Ticket::whereBetween('created_at', [$dto->start_date, $dto->end_date])
            ->get();
    }
}
