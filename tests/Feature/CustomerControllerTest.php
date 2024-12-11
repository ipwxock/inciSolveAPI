<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Insurance;
use App\Models\Issue;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerControllerTest extends TestCase
{
    use DatabaseTransactions;
    /** @test */
    public function can_list_customers()
    {
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
        ->assertJsonCount(4);
    }
    

    /** @test */
    public function can_show_a_customer()
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson('/api/customers/'. $customer->id);

        $response->assertStatus(200);
    }

    /** @test */
    public function can_list_customer_insurances()
    {
        $customer = Customer::factory()->create();
        $employee = Employee::factory()->create();

        Insurance::factory()->create([
            'employee_id' => $employee->id,
            'customer_id' => $customer->id,
            'subject_type' => 'Vida',
            'description' => 'Insurance description'

        ]);

        $response = $this->getJson('/api/customers/'. $customer->id . '/insurances');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }

    /** @test */
    public function can_list_customer_issues()
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

        $response = $this->getJson('/api/customers/'. $customer->id . '/issues');

        $response->assertStatus(200)
        ->assertJsonCount(1);
    }
}
