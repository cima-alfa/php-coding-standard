<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers;

use PhpCsFixer\ConfigInterface;
use PhpCsFixerCustomFixers\Fixers;
use PhpCsFixer\Config as PhpCsFixerConfig;
use CimaAlfaCSFixers\Fixer\BracesPositionFixer;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use CimaAlfaCSFixers\Fixer\StatementIndentationFixer;
use CimaAlfaCSFixers\Fixer\MethodArgumentSpaceFixer;
use CimaAlfaCSFixers\Fixer\ClassAndTraitVisibilityRequiredFixer;

final class Config extends PhpCsFixerConfig
{
    private array $defaultRules;

    public function __construct()
    {
        parent::__construct();

        $this->defaultRules = require_once __DIR__ . '/presets/default.php';

        $this->registerCustomFixers([
            new BracesPositionFixer,
            new ClassAndTraitVisibilityRequiredFixer,
            new MethodArgumentSpaceFixer,
            new StatementIndentationFixer(),
        ]);
        $this->registerCustomFixers(new Fixers);
        $this->setParallelConfig(ParallelConfigFactory::detect());
        $this->setRiskyAllowed(true);
        $this->setUsingCache(false);
        $this->setIndent(str_pad('', 4));
        $this->setLineEnding(PHP_EOL);
        $this->setRules();
    }

    public function setRules(array $rules = []): ConfigInterface
    {
        return parent::setRules(array_merge($this->defaultRules, $rules));
    }
}