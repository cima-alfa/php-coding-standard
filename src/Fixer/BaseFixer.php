<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Fixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Preg;

use function mb_strtolower;
use function mb_substr;

abstract class BaseFixer extends AbstractFixer
{
    final public static function name(): string
    {
        $name = Preg::replace('/(?<!^)(?=[A-Z])/', '_', mb_substr(static::class, 23, -5));

        return 'CimaAlfaCSFixers/' . mb_strtolower($name);
    }

    final public function getName(): string
    {
        return self::name();
    }
}
