<?php

namespace CimaAlfaCSFixers\Input;

final class EnvInput implements ValueInput
{
    public function __construct(
        private readonly string $name,
        private readonly string $value,
    )
    {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function escape(): self
    {
        return new self(escapeshellcmd($this->name), escapeshellarg($this->value));
    }

    public function __toString(): string
    {
        $input = $this->escape();
        
        return "{$input->getName()}={$input->getValue()}";
    }
}
