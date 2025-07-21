<?php

namespace PHPStylish\Input;

use Exception;
use Throwable;

final class InputException extends Exception
{
    public function __construct(string $message, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("\e[element]Input:\e[reset] $message", $code, $previous);
    }
}
