<?php

declare(strict_types=1);

use CimaAlfaCSFixers\Config\Config;
use CimaAlfaCSFixers\Config\Neon;
use CimaAlfaCSFixers\Config\Exception;
use CimaAlfaCSFixers\Config\ExceptionDump;
use CimaAlfaCSFixers\Helpers;
use CimaAlfaCSFixers\Message\Error;
use PhpCsFixer\Finder;

require __DIR__ . '/../vendor/autoload.php';

$rootDir = getenv('CIMA_ALFA_PHP_CODING_STANDARD_ROOT') ?: null;
$configFile = Neon::ConfigFile;

if (!is_string($rootDir) || ($rootDir = trim($rootDir)) === '' || !is_dir($rootDir)) {
    Helpers::error(Error::InvalidRootDir->internal($rootDir));
}

try {
    $config = Neon::from("$rootDir/$configFile");
    $finder = Finder::create()->in(__DIR__ . '/../examples');

    return (new Config($config->preset))->setFinder($finder)->setRules($config->rules);
} catch (Exception $e) {
    if ($e->getDump() !== ExceptionDump::Undefined) {
        dump(...$e->getDump());
    }

    Helpers::error($e->getMessage(), $e->getCode());
}
