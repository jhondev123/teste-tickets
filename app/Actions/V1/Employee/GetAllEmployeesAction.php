<?php

namespace App\Actions\V1\Employee;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;

class GetAllEmployeesAction
{
    public function __construct(private readonly Employee $employee)
    {

    }

    public function execute(): Collection
    {
        return $this->employee->limit(10)->get();
    }

}
