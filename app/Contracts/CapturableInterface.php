<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Entity\Screenshot;

interface CapturableInterface
{
    public function execute(): Screenshot;
}
