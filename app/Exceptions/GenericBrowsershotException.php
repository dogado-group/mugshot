<?php

declare(strict_types=1);

namespace App\Exceptions;

use Throwable;

class GenericBrowsershotException extends \RuntimeException
{
    public function __construct(string $message = '', Throwable $previous = null)
    {
        parent::__construct('mugshot error: ' . $message, 0, $previous);
    }

}
