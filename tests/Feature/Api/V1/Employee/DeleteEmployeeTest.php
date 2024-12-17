<?php

namespace Api\V1\Employee;

use App\Models\Employee;
use Tests\ApiTestCase;

class DeleteEmployeeTest extends ApiTestCase
{
    public function test_delete_employee()
    {
        $employee = Employee::factory()->create();
        $response = $this->delete(route('employees.destroy', ['employee' => $employee->id]));
        $response->assertStatus(204);
    }

    public function test_delete_employee_without_exists()
    {
        $response = $this->delete(route('employees.destroy', ['employee' => 1]));
        $response->assertStatus(404);
    }

}
