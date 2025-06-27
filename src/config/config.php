<?php

declare(strict_types=1);

use CimaAlfaCSFixers\Config;
use PhpCsFixer\Finder;

require __DIR__ . '/vendor/autoload.php';

$preset = getenv('CIMA_ALFA_PHP_CODING_STANDARD_PRESET') ?: null;
$finder = Finder::create()->in(__DIR__ . '/../../examples');

return (new Config($preset))->setFinder($finder);
