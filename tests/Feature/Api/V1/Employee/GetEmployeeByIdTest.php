<?php

namespace Api\V1\Employee;

use App\Models\Employee;
use Tests\ApiTestCase;

class GetEmployeeByIdTest extends ApiTestCase
{
    private Employee $employee;

    public function test_get_employee_by_id(): void
    {
        $employee = Employee::factory()->create();
        $response = $this->get(route('employees.show', ['employee' => $employee->id]));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'status',
            'message',
        ]);
    }

    public function test_get_employee_not_found():void
    {
        $response = $this->get(route('employees.show', ['employee' => 1]));
        $response->assertStatus(404);
        $response->assertJsonStructure([
            'status',
            'message',
            'errors',
        ]);

    }

}
