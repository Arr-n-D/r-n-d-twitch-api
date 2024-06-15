<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\APITestCase;

class MembersControllerTest extends APITestCase
{
    use DatabaseMigrations;


    public function test_index(): void
    {
        $response = $this->get('/members');

        $response->assertStatus(200);
    }

    public function test_show_member_no_exists(): void
    {
        $response = $this->get('/members/600');

        $response->assertStatus(404);
    }

    public function test_show_member_exists(): void
    {
        // create a member
        $member = \App\Models\Member::factory()->create();

        // get memberid
        $memberId = $member->user_id;
        $response = $this->get('/members/' . $memberId);

        $response->assertStatus(200);
    }
}
