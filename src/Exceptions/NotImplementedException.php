<?php

namespace YWatchman\LaravelEPP\Exceptions;

use Throwable;

class NotImplementedException extends EppException
{
    public function __construct(
        string $message = 'Function is not implemented yet.',
        int $code = self::NOT_IMPLEMENTED,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
