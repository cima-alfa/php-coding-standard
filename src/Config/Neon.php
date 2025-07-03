<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Config;

use Nette\Neon\Neon as NetteNeon;
use Nette\Neon\Exception as NetteNeonException;
use ReflectionClass, ReflectionProperty, ReflectionType;

final readonly class Neon
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
            $config = NetteNeon::decodeFile($file);
        } catch (NetteNeonException $e) {
            throw new NeonException($e->getMessage(), 1);
        }

        if (!is_array($config)) {
            $configType = get_debug_type($config);

            throw new NeonException("Config must be of type '\e[1;3;32marray\e[0m', '\e[1;3;31m$configType\e[0m' given.", 1);
        }

        self::validateTypes($config);
        
        $config = new self(...$config);

        self::validateValues($config);

        return $config;
    }

    /**
     * @return array<string, array{type: ReflectionType, defaultValue: mixed}>
     */
    private static function getConfigProperties(): array
    {
        $configReflection = new ReflectionClass(self::class);
        $configPropertiesReflection = $configReflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $configProperties = [];

        foreach ($configPropertiesReflection as $property) {
            $propertyName = $property->getName();
            $configProperties[$propertyName] = ['type' => $property->getType()];
            $propertyConstant = DefaultValues::class . '::' . mb_ucfirst($propertyName);

            if (defined($propertyConstant)) {
                $configProperties[$propertyName]['defaultValue'] = constant($propertyConstant);
            }
        }
        
        return $configProperties;
    }

    /**
     * @TODO Consider using Nette Schema for validation
     */
    private static function validateTypes(array &$config): void
    {
        $properties = self::getConfigProperties();
        
        foreach ($config as $propertyName => $value) {
            if (($property = $properties[$propertyName] ?? false) === false) {
                throw new NeonException("Unsupported field '\e[1;35m$propertyName\e[0m'.");
            }

            $valueType = get_debug_type($value);

            if ((string) $property['type'] !== $valueType) {
                throw new NeonException("Invalid field '\e[1;35m$propertyName\e[0m'. Must be of type '\e[1;3;32m{$property['type']}\e[0m', '\e[1;3;31m$valueType\e[0m' given.");
            }
        }

        $propertiesMissing = array_diff(
            array_keys($properties), 
            array_keys($config),
        );
        
        foreach ($propertiesMissing as $key => $propertyMissing) {
            if (array_key_exists('defaultValue', $properties[$propertyMissing])) {
                $config[$propertyMissing] = $properties[$propertyMissing]['defaultValue'];

                unset($propertiesMissing[$key]);
            }
        }

        $propertiesMissing = array_map(
            fn (string $value): string => "'\e[1;35m$value\e[0m'",
            $propertiesMissing
        );
        
        if (!empty($propertiesMissing)) {
            throw new NeonException(sprintf("The following required fields are missing: %s.", implode(', ', $propertiesMissing)));
        }
    }

    private static function validateValues(self $config): void
    {
        $presets = Presets::getDescriptions(true);

        match (false) {
            Presets::isValid($config->preset) => throw new NeonException("Provide a valid preset, '\e[1;31m$config->preset\e[0m' provided.\n\n\e[1;33mAvailable presets:\e[0m\n$presets", 1),
            default => null,
        };
    }
}