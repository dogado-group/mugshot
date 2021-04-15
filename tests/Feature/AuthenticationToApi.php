<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationToApi extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAuthentication()
    {
        $response = $this->get('/api/v1/screenshot');

        $response->assertStatus(401);
    }
}
