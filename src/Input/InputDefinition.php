<?php

namespace CimaAlfaCSFixers\Input;

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
        $args = array_filter(
            array_map(function(?Input $value): ?string {
                if ($value === null) {
                    return null;
                }
                
                return (string) $value;
            }, $this->toArray())        
        );

        $argv = $_SERVER['argv'];
        
        array_shift($argv);

        return implode(' ', [...$args, ...$argv]);
    }
}
