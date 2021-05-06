<?php

namespace Tests;

use Faker\Generator;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;
use Mockery as m;

class OrchestraTestCase extends TestCase
{
    use WithFaker;

    /**
     * @var string
     */
    protected $baseUrl = 'http://mugshot.local';

    protected function tearDown(): void
    {
        parent::tearDown();
        m::close();
    }

    /**
     * @return Generator
     */
    public function getFaker(): Generator
    {
        return $this->faker;
    }
}
