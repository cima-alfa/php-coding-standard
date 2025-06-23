<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers;

use PhpCsFixer\Preg;
use PhpCsFixer\AbstractFixer;

abstract class Fixer extends AbstractFixer
{
    final public static function name(): string
    {
        $name = Preg::replace('/(?<!^)(?=[A-Z])/', '_', \substr(static::class, 23, -5));

        return 'CimaAlfaCSFixers/' . \strtolower($name);
    }

    final public function getName(): string
    {
        return self::name();
    }
}