<?php

namespace App\Actions\V1\Employee;

use App\Http\Requests\Api\V1\Employee\UpdateEmployeeRequest;
use App\Models\Employee;

readonly class UpdateEmployeeAction
{

    public function execute(UpdateEmployeeRequest $request,Employee $employee): Employee
    {
        $employee->update($request->validated());
        return $employee;
    }

}
