<?php

declare(strict_types=1);

use CimaAlfaCSFixers\Config\Config;
use CimaAlfaCSFixers\Config\ConfigNeon;
use CimaAlfaCSFixers\Config\ConfigNeonException;
use CimaAlfaCSFixers\Helpers;
use PhpCsFixer\Finder;

require __DIR__ . '/../../vendor/autoload.php';

$rootDir = getenv('CIMA_ALFA_PHP_CODING_STANDARD_ROOT') ?: null;
$configFile = ConfigNeon::ConfigFile;

if (!is_string($rootDir) || ($rootDir = trim($rootDir)) === '' || !file_exists($rootDir)) {
    Helpers::error("Invalid root dir: '$rootDir'.");
}

try {
    $config = ConfigNeon::from("$rootDir/$configFile");
} catch (ConfigNeonException $e) {
    Helpers::error($e->getMessage(), $e->getCode());
}

$finder = Finder::create()->in(__DIR__ . '/../../examples');

return (new Config($config->preset))->setFinder($finder)->setRules($config->rules);
