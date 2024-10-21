<?php

namespace App\DTO\Reports;

class SearchTicketsByPeriodDTO
{
    public function __construct(
        public string $initialDate,
        public string $finalDate
    )
    {
    }
}
