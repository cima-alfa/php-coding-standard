<?php

namespace PHPStylish\Input;

final class ArgInput extends BaseInput
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
        if ($this->isEscaped()) {
            return $this;
        }

        return new self(escapeshellarg($this->getValue()))->setEscaped();
    }

    public function __toString(): string
    {   
        return $this->escape()->getValue();
    }
}
