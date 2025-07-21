<?php

declare(strict_types=1);

use PhpCsFixer\Finder;
use PHPStylish\Config\Config;

require_once __DIR__ . '/vendor/autoload.php';

$finder = Finder::create()->in(__DIR__ . '/examples');

return (new Config())->setFinder($finder);
