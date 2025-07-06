<?php

declare(strict_types=1);

namespace CimaAlfaCSFixers\Message;

use CimaAlfaCSFixers\Helpers;
use Exception;

enum Error: string
{
    case CustomMessage = '%s';

    case Internal = ' Internal ';

    case InstallPackages = "Install packages using \e[tip]Composer\e[reset].";

    case FixerConfigFileMissing = "The '\e[file]%s\e[reset]' file could not be resolved.";

    case InvalidRootDir = "Invalid root directory: '\e[file]%s\e[reset]'.";

    case InvalidPresetFileReturnType = "The '\e[file]%s\e[reset]' preset file must return an '\e[valid]array\e[reset]', '\e[invalid]%s\e[reset]' returned.";

    case ConfigInvalidFileReturnType = "Config must be of type '\e[valid]array\e[reset]', '\e[invalid]%s\e[reset]' given.";

    case ConfigInvalidPreset = "Provide a valid preset, '\e[error]%s\e[reset]' provided.\n\n\e[tip]Available presets:\e[reset]\n%s";

    case ConfigUnsupportedField = "Unsupported field '\e[element]%s\e[reset]'.";

    case ConfigInvalidFieldType = "Invalid field '\e[element]%s\e[reset]'. Must be of type '\e[valid]%s\e[reset]', '\e[invalid]%s\e[reset]' given.";

    case ConfigRequiredFieldsMissing = "The following required fields are missing: %s.";

    public function format(string|null ...$params): string
    {
        try {
            return sprintf($this->value, ...$params);
        } catch (Exception $e) {
            return $this->value;
        }
    }

    public function internal(string|null ...$params): string
    {
        $formats = Helpers::$consoleFormats;

        return "\e[{$formats['internal']}m" . self::Internal->value . "\e[{$formats['reset']}m " . $this->format(...$params);
    }
}