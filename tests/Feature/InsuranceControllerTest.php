<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Employee;
use Tests\TestCase;
use App\Models\Insurance;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InsuranceControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_list_all_insurances()
    {
        Insurance::factory()->count(3)->create();

        $response = $this->getJson('/api/insurances');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    /** @test */
    public function it_can_create_an_insurance()
    {

        $customer = Customer::factory()->create();
        $employee = Employee::factory()->create();

        $insuranceData = [
            'subject_type' => 'Vida',
            'description' => 'Test Insurance',
            'customer_id' => $customer->id,
            'employee_id' => $employee->id,
        ];

        $response = $this->postJson('/api/insurances', $insuranceData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['subject_type' => 'Vida']);
    }

    /** @test */
    public function it_can_show_an_insurance()
    {
        $insurance = Insurance::factory()->create();

        $response = $this->getJson("/api/insurances/{$insurance->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['customer_id' => $insurance->customer_id]);
    }

    /** @test */
    public function it_can_update_an_insurance()
    {
        $insurance = Insurance::factory()->create();

        $updateData = ['description' => 'Uheeeeeeertzel'];
        
        $response = $this->putJson("/api/insurances/{$insurance->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['description' => 'Uheeeeeeertzel']);
    }

    /** @test */
    public function it_can_delete_an_insurance()
    {
        $insurance = Insurance::factory()->create();

        $response = $this->deleteJson("/api/insurances/{$insurance->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('insurances', ['customer_id' => $insurance->customer_id, 'employee_id' => $insurance->employee_id]);
    }
}
