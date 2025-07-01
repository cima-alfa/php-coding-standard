<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Config;

use Nette\Neon\Neon;
use Nette\Neon\Exception as NeonException;
use ReflectionClass, ReflectionProperty, ReflectionType;

final readonly class ConfigNeon
{
    public const string ConfigFile = 'php-cs.neon';

    private function __construct(
        public string $preset,
        public array $rules,
    )
    {
    }

    public static function from(string $file): self
    {
        try {
            $config = Neon::decodeFile($file);
        } catch (NeonException $e) {
            throw new ConfigNeonException($e->getMessage(), 1, $e);
        }

        if (!is_array($config)) {
            throw new ConfigNeonException('Config must be an array.', 1);
        }

        $config['rules'] ??= [];

        self::validateTypes($config);

        $config = new self(...$config);

        self::validateValues($config);

        return $config;
    }

    /**
     * @return array<string, ReflectionType>
     */
    private static function getConfigProperties(): array
    {
        $configReflection = new ReflectionClass(self::class);
        $configPropertiesReflection = $configReflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $configProperties = [];

        foreach ($configPropertiesReflection as $property) {
            $configProperties[$property->getName()] = $property->getType();
        }

        return $configProperties;
    }

    /**
     * @TODO Consider using Nette Schema for validation
     */
    private static function validateTypes(array $config): void
    {
        $properties = self::getConfigProperties();
        $propertiesValidated = [];
        
        foreach ($config as $propertyName => $value) {
            if (($propertyType = $properties[$propertyName] ?? false) === false) {
                throw new ConfigNeonException("Unsupported field '$propertyName'.");
            }

            $types = explode('|', (string)$propertyType);

            if (!in_array(($valueType = get_debug_type($value)), $types, true)) {
                throw new ConfigNeonException("Invalid field '$propertyName'. Must be of type '$propertyType', '$valueType' given.");
            }

            $propertiesValidated[] = $propertyName;
        }

        $propertiesMissing = array_map(
            fn (string $value): string => "'$value'",
            array_diff(
                array_keys($properties), 
                array_keys($config),
            ),
        );
        
        if (empty($propertiesMissing)) {
            return;
        }

        throw new ConfigNeonException(sprintf("The following fileds are missing: %s.", implode(', ', $propertiesMissing)));
    }

    private static function validateValues(self $config): void
    {
        $presets = implode("\n", Config::getPresetDescriptions());

        match (false) {
            Config::isValidPreset($config->preset) => throw new ConfigNeonException("Provide a valid preset.\n\nAvailable presets:\n$presets", 1),
            default => null,
        };
    }
}