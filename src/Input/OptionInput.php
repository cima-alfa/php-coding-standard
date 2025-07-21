<?php

namespace PHPStylish\Input;

use PHPStylish\Helpers;
use PHPStylish\PCRE;

final class OptionInput extends BaseInput implements NamedInput
{
    private readonly string $name;
    
    public function __construct(
        string $name,
        private readonly ?string $value,
        private readonly bool $short = false,
    )
    {
        $this->sanitizeName($name);
    }

    private function sanitizeName(string $name): void
    {
        $this->name = Helpers::pcre(PCRE::Replace, ['#^[\s-]+|\s+$#', '', $name]);
    }

    public function getName(): string
    {   
        return $this->name;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function escape(): self
    {
        if ($this->isEscaped()) {
            return $this;
        }

        return new self(
            escapeshellcmd($this->getName()), 
            escapeshellarg("{$this->getValue()}"), 
            $this->short
        )->setEscaped();
    }

    public function __toString(): string
    {
        $input = $this->escape();
        $name = $this->short ? "-{$input->getName()}" : "--{$input->getName()}";
        
        if ($input->getValue() === escapeshellarg('')) {
            return $name;
        }
        
        return "$name={$input->getValue()}";
    }
}
