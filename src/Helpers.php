<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers;

use BackedEnum;
use CimaAlfaCSFixers\Message\Error;

final class Helpers
{
    public static array $consoleFormats = [
        'reset' => '0',
        'internal' => '1;37;41',
        'info' => '1;36',
        'success' => '1;32',
        'warning' => '1;33',
        'error' => '1;31',
        'valid' => '1;32',
        'invalid' => '1;31',
        'file' => '1;4;35',
        'element' => '1;35',
        'tip' => '1;33',
        'b' => '1',
        'b-off' => '22',
        'u' => '4',
        'u-off' => '24',
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'magenta' => '35',
        'cyan' => '36',
        'white' => '37',
    ];

    /**
     * No instances allowed
     */
    private function __construct()
    {
    }

    public static function info(BackedEnum|string $message): void
    {
        if ($message instanceof BackedEnum) {
            $message = $message->value;
        }
        
        fwrite(STDOUT, self::formatMessage("\e[info]INFO:\e[reset] $message") . "\n");
    }

    public static function success(BackedEnum|string $message): void
    {
        if ($message instanceof BackedEnum) {
            $message = $message->value;
        }
        
        fwrite(STDOUT, self::formatMessage("\e[success]SUCCESS:\e[reset] $message") . "\n");
    }

    public static function warning(BackedEnum|string $message): void
    {
        if ($message instanceof BackedEnum) {
            $message = $message->value;
        }
        
        fwrite(STDOUT, self::formatMessage("\e[warning]WARNING:\e[reset] $message") . "\n");
    }

    public static function error(BackedEnum|string $message, int $code = 1): never
    {
        if ($message instanceof BackedEnum) {
            $message = $message->value;
        }
        
        fwrite(STDERR, "\n" . self::formatMessage("\e[error]ERROR:\e[reset] $message") . "\n\n");

        exit($code);
    }

    public static function formatMessage(string $message): string
    {   
        try {
            if (!self::detectColors()) {
                return self::pcre(PCRE::Replace, ['#\e\[[^\]]+?\]#i', '', $message]);
            }
            
            $colorPatterns = array_map(fn(string $color): string => '#(' . preg_quote($color, '#') .  '(?![^;]+))(.*)#', array_keys(self::$consoleFormats));

            $message = self::pcre(PCRE::ReplaceCallback, [
                '#\e\[([^\]]+?)\]#',
                function (array $m) use ($colorPatterns): string {
                    $r = self::pcre(PCRE::ReplaceCallback, [
                        $colorPatterns,
                        function (array $m) : string {
                            $end = trim($m[2] ?? '', ';');

                            return self::$consoleFormats[$m[1]] . ($end === '' ? '' : ";$end");
                        },
                        $m[1],
                    ]);

                    if ($r === null) {
                        return $m[0];
                    }

                    return "\e[{$r}m";
                },
                $message
            ]);
            
            return $message;
        } catch (RegexpException $e) {
            if (!self::detectColors()) {
                $internal = Error::Internal->value;

                return "ERROR: $internal {$e->getMessage()}";
            }

            $formats = self::$consoleFormats;

            return "\e[{$formats['error']}mERROR:\e[{$formats['reset']}m " . Error::CustomMessage->internal($e->getMessage());
        }
    }

    /** 
     * Copied from symfony console
     * 
     * @TODO Use symfony console for IO
     */
    public static function detectColors(): bool
	{
        static $useColors;

        if (isset($useColors)) {
            return $useColors;
        }

        $detect = function(): bool {
            // Follow https://no-color.org/
            if (self::getNoColor()) {
                return false;
            }

            // Follow https://force-color.org/
            if (self::getForceColor()) {
                return true;
            }

            // Detect msysgit/mingw and assume this is a tty because detection
            // does not work correctly, see https://github.com/composer/composer/issues/9690
            if (!@stream_isatty(STDOUT) && !\in_array(strtoupper((string) getenv('MSYSTEM')), ['MINGW32', 'MINGW64'], true)) {
                return false;
            }

            if ('\\' === \DIRECTORY_SEPARATOR && @sapi_windows_vt100_support(STDOUT)) {
                return true;
            }

            if ('Hyper' === getenv('TERM_PROGRAM')
                || false !== getenv('COLORTERM')
                || false !== getenv('ANSICON')
                || 'ON' === getenv('ConEmuANSI')
            ) {
                return true;
            }

            if ('dumb' === $term = (string) getenv('TERM')) {
                return false;
            }

            // See https://github.com/chalk/supports-color/blob/d4f413efaf8da045c5ab440ed418ef02dbb28bf1/index.js#L157
            return (bool) preg_match('/^((screen|xterm|vt100|vt220|putty|rxvt|ansi|cygwin|linux).*)|(.*-256(color)?(-bce)?)$/', $term);
        };

        $useColors = $detect();

        return $useColors;
	}

    public static function getNoColor(): bool
    {
        return '' !== (($_SERVER['NO_COLOR'] ?? getenv('NO_COLOR'))[0] ?? '');
    }

    public static function getForceColor(): bool
    {
        return '' !== (($_SERVER['FORCE_COLOR'] ?? getenv('FORCE_COLOR'))[0] ?? '');
    }

    public static function normalizePath(string $path): string
    {
        return ($normalized = @realpath($path)) === false ? $path : $normalized;
    }

    public static function pcre(PCRE $function, array $params): string
    {
        $formats = self::$consoleFormats;
        $patterns = (array) $params[0];
        $where = '';
        $trace = array_filter(debug_backtrace(), function (array $trace) { 
            return $trace['function'] === 'pcre' && $trace['class'] === self::class; 
        });
        $trace = reset($trace);

        if ($trace !== false) {
            if (self::detectColors()) {
                

                $trace['file'] = "\e[{$formats['file']}m{$trace['file']}\e[{$formats['reset']}m";
                $trace['line'] = "\e[{$formats['tip']}m{$trace['line']}\e[{$formats['reset']}m";
            }

            $where = ". In '{$trace['file']}' on line {$trace['line']}";
        }

        

        if (self::detectColors()) {
            $patterns = array_map(fn(string $pattern): string => "\e[{$formats['yellow']}m$pattern\e[{$formats['reset']}m", $patterns);
        }

        set_error_handler(fn(int $code, string $message): never => throw new RegexpException($message . ' in pattern: ' . implode(' or ', $patterns) . "$where.", $code));
        
        $function = $function->value;
        $result = $function(...$params);

        restore_error_handler();

        if (($code = preg_last_error()) && $result === null) {
			throw new RegexpException(preg_last_error_msg() . ' (pattern: ' . implode(' or ', $patterns) .  ") $where.", $code);
		}

        return $result;
    }
}