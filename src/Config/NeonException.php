<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Config;

use Throwable;

final class NeonException extends Exception
{
    protected bool $overrideMessage = true;

    public function __construct(string $message, int $code = 0, ?Throwable $previous = null, ...$dump)
    {
        parent::__construct("\e[file]" . Neon::ConfigFile . "\e[u-off]:\e[reset] $message", $code, $previous, ...$dump);
    }
}