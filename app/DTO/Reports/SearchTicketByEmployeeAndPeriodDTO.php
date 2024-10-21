<?php

namespace App\DTO\Reports;

class SearchTicketByEmployeeAndPeriodDTO
{
    public function __construct(
        public string $start_date,
        public string $end_date,
        public ?int $employee_id = null,

    )
    {
    }
}
