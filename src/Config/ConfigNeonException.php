<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Config;

use Exception;
use Throwable;

final class ConfigNeonException extends Exception
{
    public function __construct(string $message, int $code = 0, Throwable|null $throwable = null)
    {
        parent::__construct(ConfigNeon::ConfigFile . ': ' . $message, $code, $throwable);
    }
}