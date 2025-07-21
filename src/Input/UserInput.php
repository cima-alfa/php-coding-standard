<?php

namespace PHPStylish\Input;

use PHPStylish\Input\Definition\Input;
use PHPStylish\Input\Definition\Type;

final class UserInput implements Definition
{
    /**
     * @param string[]|null $suggestValues
     */
    public function __construct(
        private readonly Input $input, 
        private readonly Type $type,
        private readonly ?string $description = null,
        private readonly ?array $suggestValues = null,
    )
    {
    }

    public function getDefaultValue(): string
    {
        return $this->type->defaultValue();
    }

    public function toArray(): array
    {
        return [];
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
