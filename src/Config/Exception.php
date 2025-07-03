<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Config;

use Exception as BaseException;
use Throwable;

class Exception extends BaseException
{
    protected bool $overrideMessage = false;
    private mixed $dump;

    public function __construct(string $message, int $code = 0, ?Throwable $previous = null, ...$dump)
    {
        if (!empty($dump)) {
            $this->dump = $dump;
        }

        if (!$this->overrideMessage) {
            parent::__construct("\e[1;35mConfig:\e[0m $message", $code, $previous);

            return;
        }

        parent::__construct($message, $code, $previous);
    }

    public function getDump(): mixed
    {
        return isset($this->dump) ? $this->dump :  ExceptionDump::Undefined;
    }
}
