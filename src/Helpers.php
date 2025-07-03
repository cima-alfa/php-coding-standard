<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers;

final class Helpers
{
    public static function info(string $message): void
    {
        fwrite(STDOUT, "\e[1;36mINFO:\e[0m $message\n");
    }

    public static function success(string $message): void
    {
        fwrite(STDOUT, "\e[1;32mSUCCESS:\e[0m $message\n");
    }

    public static function warning(string $message): void
    {
        fwrite(STDOUT, "\e[1;33mWARNING:\e[0m $message\n");
    }

    public static function error(string $message, int $code = 1): never
    {
        fwrite(STDERR, "\n\e[1;31mERROR:\e[0m $message\n\n");

        exit($code);
    }
}