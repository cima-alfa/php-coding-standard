<?php

namespace CimaAlfaCSFixers\Input;

use Stringable;

interface Input extends Stringable
{
    public function getValue(): string;
    public function escape(): self;
}
