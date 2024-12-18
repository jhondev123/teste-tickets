<?php

namespace App\Actions\V1\Employee;

use App\Models\Employee;

class DeleteEmployeeAction
{
    public function __construct(private Employee $employee)
    {
    }

    public function execute(string $employeeId): bool
    {
        $employee = $this->employee->find($employeeId);
        if (!$employee) {
            throw new \DomainException('Employee not found', 404);
        }
        return $employee->delete();

    }


}
