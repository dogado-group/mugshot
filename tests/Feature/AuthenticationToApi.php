<?php

declare(strict_types=1);

namespace Tests\Feature;

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
