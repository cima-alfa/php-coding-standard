<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers;

use CimaAlfaCSFixers\Fixer\BracesPositionFixer;
use CimaAlfaCSFixers\Fixer\ClassAndTraitVisibilityRequiredFixer;
use CimaAlfaCSFixers\Fixer\MethodArgumentSpaceFixer;
use CimaAlfaCSFixers\Fixer\StatementIndentationFixer;
use InvalidArgumentException;
use PhpCsFixer\Config as PhpCsFixerConfig;
use PhpCsFixer\ConfigInterface;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;
use PhpCsFixerCustomFixers\Fixers;

final class Config extends PhpCsFixerConfig
{
    public const array Presets = [
        'default' => 'Based on the Nette Coding standard',
        'empty' => 'No rules defined by default',
    ];

    private array $fixerRules;

    public function __construct(?string $name = null)
    {
        $name ??= self::getPresets()->default;

        if (!self::isValidPreset($name)) {
            throw new InvalidArgumentException("Invalid preset provided.");
        }

        parent::__construct($name);
        
        match ($name) {
            'empty' => $this->setRulesEmpty(),
            default => $this->setRulesDefault(),
        };
        
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

    private function setRulesEmpty(): void
    {
        $this->fixerRules = [];
    }

    private function setRulesDefault(): void
    {
        $this->fixerRules = require_once __DIR__ . '/config/presets/default.php';

        $this->registerCustomFixers([
            new BracesPositionFixer,
            new ClassAndTraitVisibilityRequiredFixer,
            new MethodArgumentSpaceFixer,
            new StatementIndentationFixer(),
        ]);

        $this->registerCustomFixers(new Fixers);
    }

    public static function getPresets(): object
    {
        $presets = [];
        
        foreach (array_keys(self::Presets) as $preset) {
            $presets[$preset] = $preset;
        }

        return (object) $presets;
    }

    public static function getPresetDescriptions(): array
    {
        $presets = [];

        foreach (self::Presets as $preset => $description) {
            $presets[] = "$preset: $description";
        }

        return $presets;
    }

    public static function isValidPreset(string $preset): bool
    {
        return array_key_exists($preset, self::Presets);
    }
}
