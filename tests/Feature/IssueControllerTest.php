<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Issue;
use App\Models\Insurance;

class IssueControllerTest extends TestCase
{

    use DatabaseTransactions;


    /**
     * A basic test example.
     */

    /** @test */
    public function it_can_list_all_issues()
    {
        $issues = Issue::factory(3)->create();

        $response = $this->getJson('/api/issues');

        $response->assertStatus(200)->assertJsonCount(5);
    }

    /** @test */
    public function it_can_create_an_issue()
    {
        $insurance = Insurance::factory()->create();

        $issueData = [
            'subject' => 'Test Issue',
            'status' => 'Abierta',
            'insurance_id' => $insurance->id,
        ];

        $response = $this->postJson('/api/issues', $issueData);

        $response->assertStatus(201)->assertJsonFragment(['subject' => 'Test Issue']);
    }
    
    /** @test */
    public function it_can_show_an_issue()
    {
        $issue = Issue::factory()->create();

        $response = $this->getJson('/api/issues/' . $issue->id);

        $response->assertStatus(200)->assertJsonFragment(['subject' => $issue->subject]);
    }


    /** @test */
    public function it_can_update_an_issue()
    {
        $issue = Issue::factory()->create();

        $issueData = [
            'subject' => 'Test Issue Updated',
            'status' => 'Cerrada',
        ];

        $response = $this->putJson('/api/issues/' . $issue->id, $issueData);

        $response->assertStatus(200)->assertJsonFragment(['subject' => 'Test Issue Updated']);
    }

    /** @test */
    public function it_can_delete_an_issue()
    {
        $issue = Issue::factory()->create();

        $response = $this->deleteJson('/api/issues/' . $issue->id);

        $response->assertStatus(204);
    }

}