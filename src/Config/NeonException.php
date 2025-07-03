<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Config;

use Throwable;

final class NeonException extends Exception
{
    protected bool $overrideMessage = true;

    public function __construct(string $message, int $code = 0, ?Throwable $previous = null, ...$dump)
    {
        parent::__construct("\e[1;4;35m" . Neon::ConfigFile . "\e[0;1;35m:\e[0m $message", $code, $previous, ...$dump);
    }
}