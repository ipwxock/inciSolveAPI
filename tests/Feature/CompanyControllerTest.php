<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Company;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function test_factory_creates_companies()
        {
            Company::factory()->count(3)->create();
    
            $this->assertEquals(5, Company::count());
        }

    /** @test */
    public function it_can_list_all_companies()
    {
        Company::factory()->count(3)->create();

        $response = $this->getJson('/api/companies');

        $response->assertStatus(200)
                 ->assertJsonCount(5);
    }

    /** @test */
    public function it_can_create_a_company()
    {
        $companyData = [
            'name' => 'Test Company',
            'description' => 'A test company',
        ];

        $response = $this->postJson('/api/companies', $companyData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['name' => 'Test Company']);
    }

    /** @test */
    public function it_can_show_a_company()
    {
        $company = Company::factory()->create();

        $response = $this->getJson("/api/companies/{$company->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $company->id]);
    }

    /** @test */
    public function it_can_update_a_company()
    {
        $company = Company::factory()->create();

        $updateData = ['name' => 'Updated Company'];

        $response = $this->putJson("/api/companies/{$company->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['name' => 'Updated Company']);
    }

    /** @test */
    public function it_can_delete_a_company()
    {
        $company = Company::factory()->create();

        $response = $this->deleteJson("/api/companies/{$company->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('companies', ['id' => $company->id]);
    }
}
