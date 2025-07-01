<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers;

final class Helpers
{
    public static function info(string $message): void
    {
        fwrite(STDOUT, "Info: $message\n");
    }

    public static function success(string $message): void
    {
        fwrite(STDOUT, "Success: $message\n");
    }

    public static function warning(string $message): void
    {
        fwrite(STDOUT, "Warning: $message\n");
    }

    public static function error(string $message, int $code = 1): void
    {
        fwrite(STDERR, "Error: $message\n");

        exit($code);
    }

    public static function d(...$data): void
    {
        fwrite(STDOUT, "Dump:\n");
        
        foreach ($data as $dump) {
            fwrite(STDOUT, var_export($dump, true) . "\n");
        }
    }

    public static function dd(...$data): void
    {
        self::d(...$data);

        exit(0);
    }
}