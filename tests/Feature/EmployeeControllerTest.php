<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Insurance;
use App\Models\Issue;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class EmployeeControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    /** @test */
    public function can_list_employees()
    {
        Employee::factory()->count(3)->create();

        $response = $this->getJson('/api/employees');

        $response->assertStatus(200)
        ->assertJsonCount(5);
    }
    

    /** @test */
    public function can_show_an_employee()
    {
        $employee = Employee::factory()->create();

        $response = $this->getJson('/api/employees/'. $employee->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function can_list_employee_insurances()
    {
        $customer = Customer::factory()->create();
        $employee = Employee::factory()->create();

        Insurance::factory()->create([
            'employee_id' => $employee->id,
            'customer_id' => $customer->id,
            'subject_type' => 'Vida',
            'description' => 'Insurance description'

        ]);

        $response = $this->getJson('/api/employees/'. $employee->id . '/insurances');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    /** @test */
    public function can_list_employee_issues()
    {
        $customer = Customer::factory()->create();
        $employee = Employee::factory()->create();

        $insurance = Insurance::factory()->create([
            'employee_id' => $employee->id,
            'customer_id' => $customer->id,
            'subject_type' => 'Vida',
            'description' => 'Insurance description'

        ]);

        Issue::factory()->create([
            'insurance_id' => $insurance->id,
            'subject' => 'Issue subject',
            'status' => 'Pendiente'
        ]);

        $response = $this->getJson('/api/employees/'. $employee->id . '/issues');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }
}
