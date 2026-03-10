<?php

declare(strict_types=1);

namespace Tests;

use Faker\Generator;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery as m;
use Orchestra\Testbench\TestCase;

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

    public function getFaker(): Generator
    {
        return $this->faker;
    }
}
