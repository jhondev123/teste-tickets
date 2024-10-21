<?php

namespace App\DTO\Reports;

class SearchTicketByEmployeeAndPeriodDTO
{
    public function __construct(
        public int $employee_id,
        public string $start_date,
        public string $end_date
    )
    {
    }
}
