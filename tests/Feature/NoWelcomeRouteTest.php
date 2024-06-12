<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NoWelcomeRouteTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_no_expose_web(): void
    {
        $response = $this->get('/');

        $response->assertStatus(404);
    }
}
