<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Ticket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $employees = Employee::factory(1000)->create();
        foreach ($employees as $employee) {
            Ticket::factory(1)->create(['employee_id' => $employee->id]);
        }
    }
}
