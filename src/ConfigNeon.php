<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers;

use Exception;
use Nette\Neon\Neon;

final readonly class ConfigNeon
{
    private function __construct(
        public string $preset,
        public array $rules,
    )
    {
    }

    public static function from(string $file): ?self
    {
        try {
            $config = Neon::decodeFile($file);
        } catch (Exception $e) {
            return null;
        }

        $config['rules'] ??= [];

        return new self(...$config);
    }
}