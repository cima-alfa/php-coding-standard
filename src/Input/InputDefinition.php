<?php

namespace PHPStylish\Input;

use ReflectionClass;
use ReflectionProperty;

final class InputDefinition implements Definition
{
    public function __construct(
        private readonly EnvInput $root,
        private readonly ?EnvInput $color,
        private readonly EnvInput $xdebug,
        private readonly ArgInput $phpBinary,
        private readonly ArgInput $csBinary,
        private readonly UserInputDefinition $userInput,
    )
    {}

    public function toArray(): array
    {
        $reflection = new ReflectionClass($this);
        $properties = [];

        foreach ($reflection->getProperties(ReflectionProperty::IS_READONLY) as $property) {
            $properties[$property->getName()] = $property->getRawValue($this);
        }

        return $properties;
    }

    public function __toString(): string
    {
        $input = array_filter(
            array_map(function(Input|Definition|null $value): ?string {
                if ($value === null) {
                    return null;
                }
                
                return (string) $value;
            }, $this->toArray())        
        );

        return implode(' ', $input);
    }
}
