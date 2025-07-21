<?php

namespace PHPStylish\Input\Definition;

use PHPStylish\Input\InputException;
use PHPStylish\Message\Error;

enum Type
{
    case Optional;
    case Required;

    /**
     * @return ($setValue is false ? string : self)
     */
    public function defaultValue(int|string|false $setValue = false): self|string
    {
        if ($this->name !== self::Optional->name) {
            $trace = array_filter(debug_backtrace(), fn(array $info): bool => ($info['object'] ?? null) === $this && $info['function'] === 'defaultValue');
            $trace = reset($trace);

            throw new InputException(Error::NonOptionalUserInputDefaultValue->format($trace['file'], $trace['line']));
        }

        static $string;

        if (!isset($string) && $setValue !== false) {
            $string = (string) $setValue;
        }

        return $setValue === false ? $string : $this;
    }
}
