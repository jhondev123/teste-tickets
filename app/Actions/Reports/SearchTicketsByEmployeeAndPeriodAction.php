<?php

namespace App\Actions\Reports;

use App\DTO\Reports\SearchTicketByEmployeeAndPeriodDTO;
use App\Models\Ticket;

class SearchTicketsByEmployeeAndPeriodAction
{
    public static function execute(SearchTicketByEmployeeAndPeriodDTO $dto)
    {
        return Ticket::where('employee_id', $dto->employee_id)
            ->whereBetween('created_at', [$dto->start_date, $dto->end_date])
            ->get();
    }
}
