<?php

namespace PHPStylish;

enum PCRE: string
{

    /**
     * @param string|string[] $pattern
     * @param string|string[] $replacement
     * @param string|string[] $subject
     * @param int $limit [optional]
     * @param int &$count [optional]
     * @return string|string[]|null
     * @see preg_replace()
     */
    case Replace = 'preg_replace';

    /**
     * @param string|string[] $pattern
     * @param callable $callback
     * @param string|string[] $subject
     * @param int $limit [optional]
     * @param int &$count [optional]
     * @param int $flags [optional]
     * @return string|string[]|null
     * @see preg_replace_callback()
     */
    case ReplaceCallback = 'preg_replace_callback';

    /**
     * @param string $pattern
     * @param string $subject
     * @param string[] &$matches [optional]
     * @param int $flags [optional]
     * @param int $offset [optional]
     * @return int|false
     * @see preg_match()
     */
    case Match = 'preg_match';
}
