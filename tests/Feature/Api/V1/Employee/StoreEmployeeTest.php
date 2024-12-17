<?php

namespace Api\V1\Employee;

use App\Models\Employee;
use Tests\ApiTestCase;

class StoreEmployeeTest extends ApiTestCase
{
    public function test_store_employee(): void
    {
        $response = $this->post(route('employees.store'), [
            'name' => 'John Doe',
            'cpf' => '490.778.680-89'
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data',
            'status',
            'message',
        ]);
    }

    public function test_store_employee_with_invalid_cpf(): void
    {
        $response = $this->post(route('employees.store'), [
            'name' => 'John Doe',
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

    public function test_store_employee_with_cpf_without_pontution():void
    {
        $response = $this->post(route('employees.store'), [
            'name' => 'John Doe',
            'cpf' => '49077868089'
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data'
        ]);

    }

    public function test_store_employee_without_name(): void
    {
        $response = $this->post(route('employees.store'), [
            'cpf' => '490.778.680-89'
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'name',
            ],
        ]);
    }

    public function test_store_employee_without_situation():void
    {
        $response = $this->post(route('employees.store'), [
            'name' => 'John Doe',
            'cpf' => '490.778.680-89',
        ]);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'message',
            'data'
        ]);
    }

    public function test_store_employee_with_cpf_alredy_exists()
    {
        $employee = Employee::factory()->create([
            'cpf' => '490.778.680-89'
        ]);
        $response = $this->post(route('employees.store'), [
            'name' => 'John Doe',
            'cpf' => '490.778.680-89'
        ]);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'cpf',
            ],
        ]);

    }


}
