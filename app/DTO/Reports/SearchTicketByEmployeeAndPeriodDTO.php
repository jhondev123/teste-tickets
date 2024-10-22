<?php

namespace App\DTO\Reports;

class SearchTicketByEmployeeAndPeriodDTO
{
    public function __construct(
        public ?string $start_date = null,
        public ?string $end_date = null,
        public ?int $employee_id = null,

    )
    {
    }
}
