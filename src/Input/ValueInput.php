<?php

namespace CimaAlfaCSFixers\Input;

interface ValueInput extends Input
{
    public function getName(): string;
    public function getValue(): string;
}
