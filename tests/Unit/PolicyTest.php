<?php

namespace Tests\Feature\Policies;

use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Http\Policies\CompanyPolicy;
use App\Http\Policies\CustomerPolicy;
use App\Http\Policies\EmployeePolicy;

class PolicyTest extends TestCase
{
    /** @test */
    public function admin_can_create_company()
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $this->assertTrue(CompanyPolicy::create($admin));
    }

    /** @test */
    public function employee_cannot_create_company()
    {
        $employee = User::factory()->create(['role' => 'Empleado']);

        $this->assertFalse(CompanyPolicy::create($employee));
    }

    /** @test */
    public function manager_can_view_all_companies()
    {
        $manager = User::factory()->create(['role' => 'Manager']);

        $this->assertTrue(CompanyPolicy::viewAll($manager));
    }

    /** @test */
    public function admin_can_delete_company()
    {
        $admin = User::factory()->create(['role' => 'Admin']);

        $this->assertTrue(CompanyPolicy::delete($admin));
    }

    /** @test */
    public function customer_cannot_update_company()
    {
        $customer = User::factory()->create(['role' => 'Cliente']);
        $company = Company::factory()->create();

        $this->assertFalse(CompanyPolicy::update($customer, $company));
    }

    /** @test */
    public function employee_can_view_customers()
    {
        $employee = User::factory()->create(['role' => 'Empleado']);

        $this->assertTrue(CustomerPolicy::view($employee));
    }

    /** @test */
    public function manager_can_update_employee()
    {
        $manager = User::factory()->create(['role' => 'Manager']);

        $this->assertTrue(EmployeePolicy::update($manager));
    }
}
