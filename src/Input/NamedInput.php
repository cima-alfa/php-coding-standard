<?php

namespace PHPStylish\Input;

interface NamedInput extends Input
{
    public function getName(): string;
}
