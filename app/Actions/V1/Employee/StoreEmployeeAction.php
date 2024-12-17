<?php

namespace App\Actions\V1\Employee;

use App\Enums\EmployeeSituation;
use App\Http\Requests\Api\V1\Employee\StoreEmployeeRequest;
use App\Models\Employee;

readonly class StoreEmployeeAction
{
    public function __construct(private Employee $employee)
    {

    }

    public function execute(StoreEmployeeRequest $request): Employee
    {

        $data = $request->validated();
        $data['situation'] = EmployeeSituation::Active->value;

        return $this->employee->create($data);

    }

}
