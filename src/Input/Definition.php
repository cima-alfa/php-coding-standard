<?php

namespace CimaAlfaCSFixers\Input;

use Stringable;

interface Definition extends Stringable
{
    /**
     * @return array<string, ?Input>
     */
    public function toArray(): array;
}
