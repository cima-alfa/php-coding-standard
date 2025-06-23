<?php

use PhpCsFixer\Finder;
use CimaAlfaCSFixers\Config;

require __DIR__ . '/vendor/autoload.php';

return (new Config)
    ->setFinder(
        Finder::create()
            ->in(__DIR__ . '/examples')
    );