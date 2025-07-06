<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Config;

use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Config as PhpCsFixerConfig;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixerCustomFixers\Fixers as PhpCsFixerCustomFixers;
use CimaAlfaCSFixers\Fixer\Nette\Fixers as NetteFixers;
use CimaAlfaCSFixers\Message\Error;

final class Config extends PhpCsFixerConfig
{
    private string $preset;
    private array $fixerRules;

    public function __construct(?string $preset = null)
    {
        $preset ??= Presets::Default->getName();

        if (!Presets::isValid($preset)) {
            $presetDescriptions = Presets::getDescriptions(true);
            
            throw new Exception(Error::ConfigInvalidPreset->format($preset, $presetDescriptions));
        }

        parent::__construct($preset);

        $this->preset = $preset;
        $this->fixerRules = $this->getFixerRules();
        
        match ($preset) {
            'cima-alfa' => $this->setRulesCimaAlfa(),
            default => null,
        };
        
        $this->setUnsupportedPhpVersionAllowed(true);
        $this->setParallelConfig(ParallelConfigFactory::detect());
        $this->setRiskyAllowed(true);
        $this->setUsingCache(false);
        $this->setIndent(mb_str_pad('', 4));
        $this->setLineEnding(PHP_EOL);
        $this->setRules();
    }

    public function setRules(array $rules = []): ConfigInterface
    {
        return parent::setRules(array_merge($this->fixerRules, $rules));
    }

    private function setRulesCimaAlfa(): void
    {
        $this->registerCustomFixers(new NetteFixers);
        $this->registerCustomFixers(new PhpCsFixerCustomFixers);
    }

    private function getFixerRules(): array
    {
        $presetFile = __DIR__ . "/../../cs/presets/$this->preset.php";
        $fixerRules = [];

        if (is_file($presetFile)) {
            $fixerRules = require_once $presetFile;
        }
        
        if (!is_array($fixerRules)) {
            $returnType = get_debug_type($fixerRules);

            throw new Exception(Error::InvalidPresetFileReturnType->internal($this->preset, $returnType));
        }

        return $fixerRules;
    }
}
