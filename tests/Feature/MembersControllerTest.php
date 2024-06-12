<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\APITestCase;

class MembersControllerTest extends APITestCase
{
    use DatabaseMigrations;
   
    public function test_fake_data_api_call(): void
    {
        $response = $this->get('/api/members/fake');

        // assert that the response contains specific keys
        $response->assertJsonStructure([
            'last_name',
            'address',
            'phone',
            'email',
            'license',
            'license_class',
            'license_suspended',
            'revoking_reason',
            'current_employer',
            'employer_name',
            'employer_address',
            'employer_postcode',
            'employer_email',
            'employer_phone',
            'references'
        ]);

        $response->assertStatus(200);
    }

    public function test_index(): void
    {
        $response = $this->get('/api/members');

        $response->assertStatus(200);
    }

    public function test_show_member_no_exists(): void
    {
        $response = $this->get('/api/members/600');

        $response->assertStatus(404);
    }

    public function test_show_member_exists(): void
    {
        // create a member
        $member = \App\Models\Member::factory()->create();

        // get memberid
        $memberId = $member->user_id;
        $response = $this->get('/api/members/' . $memberId);

        $response->assertStatus(200);
    }
}
