<?php

namespace PHPStylish\Input;

use Stringable;

interface Definition extends Stringable
{
    /**
     * @return array<string, ?Input>
     */
    public function toArray(): array;
}
