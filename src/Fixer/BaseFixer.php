<?php

declare(strict_types=1);

namespace PHPStylish\Fixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Preg;

abstract class BaseFixer extends AbstractFixer
{
    final public static function name(): string
    {
        $namespace = __NAMESPACE__;
        $class = static::class;

        $prefix = explode('\\', $namespace, 2)[0];
        $name = trim(Preg::replace('/\\\+/', '_', str_replace($namespace, '', $class)), '_');
        $name = Preg::replace('/(?<!^)(?=[A-Z])/', '_', $name);

        return "$prefix/" . mb_strtolower(Preg::replace('/_+/', '_', $name));
    }

    final public function getName(): string
    {
        return self::name();
    }
}
