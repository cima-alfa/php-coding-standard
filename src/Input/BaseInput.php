<?php

namespace PHPStylish\Input;

abstract class BaseInput implements Input
{
    private readonly true $escaped;

    final public function isEscaped(): bool
    {
        return isset($this->escaped);
    }

    final public function setEscaped(): self
    {
        if (!isset($this->escaped)) {
            $this->escaped = true;
        }

        return $this;
    }
}
