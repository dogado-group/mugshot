<?php

declare(strict_types=1);

namespace App\Contracts;

use App\DataTransferObject\ScreenshotData;

interface CapturableInterface
{
    public function execute(): ScreenshotData;
}
