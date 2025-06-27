<?php

declare(strict_types=1);

use CimaAlfaCSFixers\Config;
use PhpCsFixer\Finder;

require __DIR__ . '/vendor/autoload.php';

$finder = Finder::create()->in(__DIR__ . '/examples');

return (new Config())->setFinder($finder);
