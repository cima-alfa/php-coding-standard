<?php

namespace PHPStylish\Input;

use PHPStylish\Helpers;
use PHPStylish\Message\Error;
use PHPStylish\PCRE;

final class UserInputDefinition implements Definition
{
    /**
     * @var UserInput[]
     */
    private readonly array $input;

    /**
     * @var (ArgInput|OptionInput)[]
     */
    private readonly array $userInput;

    public function __construct(UserInput ...$input)
    {
        $this->input = $input;
        $this->userInput = $this->getUserInput();
    }

    /**
     * @return (ArgInput|OptionInput)[]
     */
    private function getUserInput(): array
    {
        $argv = $_SERVER['argv'];

        array_shift($argv);

        $input = [];
        $invalidOptions = [];

        foreach ($argv as $userInput) {
            $optionData = [];
            $isOption = Helpers::pcre(PCRE::Match, ['#^(?<type>-+)(?<name>[^-=]+)(?:=(?<value>.*))?#', $userInput, &$optionData]);

            if ($isOption) {
                if (mb_strlen($optionData['type']) > 2) {
                    $invalidOptions[] = $userInput;

                    continue;
                }

                $input[] = new OptionInput($optionData['name'], $optionData['value'] ?? null, $optionData['type'] === '-');

                continue;
            }

            $input[] = new ArgInput($userInput);
        }

        if (!empty($invalidOptions)) {
            throw new InputException(Error::MalformedInputOptions->format(
                implode(', ', array_map(fn(string $value): string => "'\e[element]$value\e[reset]'", $invalidOptions))
            ));
        }

       return $input;
    }

    public function toArray(): array
    {
        $input = [];

        foreach ($this->userInput as $userInput) {
            $name = $userInput->getValue();
            
            if ($userInput instanceof OptionInput) {
                $name = "option:{$userInput->getName()}";
            }

            $input[Helpers::toPascalCase($name)] = $userInput;
        }

        return $input;
    }

    public function __toString(): string
    {
        $input = array_filter(
            array_map(function(Input $value): ?string {
                return (string) $value;
            }, $this->toArray())        
        );

        return implode(' ', $input);
    }
}
