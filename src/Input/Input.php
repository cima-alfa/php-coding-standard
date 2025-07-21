<?php

namespace PHPStylish\Input;

use Stringable;

interface Input extends Stringable
{
    public function getValue(): mixed;
    public function isEscaped(): bool;
    public function setEscaped(): self;
    public function escape(): self;
}
