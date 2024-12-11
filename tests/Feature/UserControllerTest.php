<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_list_all_users()
    {
        User::factory()->count(3)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
                 ->assertJsonCount(7);
    }

    /** @test */
    public function it_can_create_an_admin()
    {

        $userData = [
            'dni' => '11223344A',
            'first_name' => 'Jehn',
            'last_name' => 'Doe',
            'email' => 'hortzol@hertzol.com',
            'password' => 'password123',
            'role' => 'Admin',
        ];
            

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['first_name' => 'Jehn']);
    }

    public function it_can_create_a_manager()
    {
        $userData = [
            'dni' => '11223344A',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'asdasd@asd.com',
            'password' => 'password123',
            'role' => 'Manager',
            'company_id' => 7,
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['company_id' => 7]);
    }

    public function it_can_create_an_employee()
    {
        $userData = [
            'dni' => '11223344A',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'esdesd@asd.com',
            'password' => 'password123',
            'role' => 'Empleado',
            'company_id' => 8,
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['first_name' => 'John']);
    }

    public function it_can_create_a_customer()
    {
        $userData = [
            'dni' => '11223344A',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'osdosd@asd.com',
            'password' => 'password123',
            'role' => 'Cliente',
            'phone_number' => '123456789',
            'address' => 'Calle Falsa 123',
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
                 ->assertJsonFragment(['first_name' => 'John']);
    }



    /** @test */
    public function it_can_show_a_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['id' => $user->id]);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $user = User::factory()->create();

        $updateData = ['first_name' => 'UpdatedName'];

        $response = $this->putJson("/api/users/{$user->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJsonFragment(['first_name' => 'UpdatedName']);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/users/{$user->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }


    
}
