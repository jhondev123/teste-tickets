<?php

namespace Api\V1\Employee;

use App\Models\Employee;
use Tests\ApiTestCase;

class UpdateEmployeeTest extends ApiTestCase
{
    public function test_update_employee()
    {
        $employee = Employee::factory()->create();
        $response = $this->put(route('employees.update', ['employee' => $employee->id]), [
            'name' => 'John Doe'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'name',
                'cpf',
                'created_at',
                'updated_at',
            ],
            'status',
            'message',
        ]);
        $employee->refresh();
        $this->assertEquals('John Doe', $employee->name);
    }

    public function test_update_employee_with_invalid_cpf():void
    {
        $employee = Employee::factory()->create();
        $response = $this->put(route('employees.update', ['employee' => $employee->id]), [
            'cpf' => '12345678901'
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'cpf',
            ],
        ]);
    }

    public function test_update_employee_with_cpf_alredy_exists()
    {
        $employee = Employee::factory()->create();
        $employee2 = Employee::factory()->create();
        $response = $this->put(route('employees.update', ['employee' => $employee->id]), [
            'cpf' => $employee2->cpf
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'cpf',
            ],
        ]);

    }

    public function test_update_employee_with_situation_unavaliable()
    {
        $employee = Employee::factory()->create();
        $response = $this->put(route('employees.update', ['employee' => $employee->id]), [
            'situation' => 'X'
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'situation',
            ],
        ]);

    }

}
