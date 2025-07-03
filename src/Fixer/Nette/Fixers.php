<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Fixer\Nette;

use Generator;
use DirectoryIterator;
use PhpCsFixer\Fixer\FixerInterface;

final class Fixers implements \IteratorAggregate
{
    /**
     * @return Generator<FixerInterface>
     */
    public function getIterator(): Generator
    {
        $classNames = [];

        foreach (new DirectoryIterator(__DIR__) as $fileInfo) {
            $fileName = $fileInfo->getBasename('.php');

            if (in_array($fileName, ['.', '..', 'AbstractFixer', 'AbstractTypesFixer', 'Fixers'], true)) {
                continue;
            }

            $classNames[] = __NAMESPACE__ . "\\$fileName";
        }

        sort($classNames);

        foreach ($classNames as $className) {
            $fixer = new $className();
            
            assert($fixer instanceof FixerInterface);

            yield $fixer;
        }
    }
}