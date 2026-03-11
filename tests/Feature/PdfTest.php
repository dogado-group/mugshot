<?php

declare(strict_types=1);

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class PdfTest extends TestCase
{
    #[Test]
    public function unauthenticatedRequestRejected(): void
    {
        $this->postJson('/api/v1/pdf')
            ->assertStatus(401);
    }
}
