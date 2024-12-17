<?php

namespace App\Actions\V1\Employee;

use App\Models\Employee;

class GetEmployeeByIdAction
{
    public function __construct(private Employee $employee)
    {

    }

    public function execute(string $id): Employee|null
    {
        return $this->employee->find($id);

    }

}
