<?php

namespace CimaAlfaCSFixers\Input;

final class ArgInput implements Input
{
    public function __construct(
        private readonly string $value,
    )
    {}

    public function getValue(): string
    {
        return $this->value;
    }

    public function escape(): self
    {
        return new self(escapeshellarg($this->value));
    }

    public function __toString(): string
    {
        $input = $this->escape();
        
        return $input->getValue();
    }
}
