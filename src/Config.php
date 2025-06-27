<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers;

use CimaAlfaCSFixers\Fixer\BracesPositionFixer;
use CimaAlfaCSFixers\Fixer\ClassAndTraitVisibilityRequiredFixer;
use CimaAlfaCSFixers\Fixer\MethodArgumentSpaceFixer;
use CimaAlfaCSFixers\Fixer\StatementIndentationFixer;
use PhpCsFixer\Config as PhpCsFixerConfig;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixerCustomFixers\Fixers;

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
        $this->setIndent(mb_str_pad('', 4));
        $this->setLineEnding(PHP_EOL);
        $this->setRules();
    }

    public function setRules(array $rules = []): ConfigInterface
    {
        return parent::setRules(array_merge($this->defaultRules, $rules));
    }
}
