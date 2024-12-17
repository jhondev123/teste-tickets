<?php

namespace Api\V1\Employee;

use Tests\ApiTestCase;

class GetAllEmployeesTest extends ApiTestCase
{
    public function test_get_all_employees(): void
    {
        $response = $this->get(route('employees.index'));
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data',
            'status',
            'message',
        ]);
    }
}
