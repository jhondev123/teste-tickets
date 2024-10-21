<?php

namespace App\Actions\Reports;

use App\DTO\Reports\SearchTicketByEmployeeAndPeriodDTO;
use App\Models\Ticket;

class SearchTicketsByEmployeeAndPeriodAction
{
    public static function execute(SearchTicketByEmployeeAndPeriodDTO $dto)
    {
        $query = Ticket::whereBetween('created_at', [$dto->start_date, $dto->end_date]);
        if ($dto->employee_id) {
            $query->where('employee_id', $dto->employee_id);
        }
        return $query->get();
    }
}
