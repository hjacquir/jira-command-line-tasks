<?php

declare(strict_types=1);

namespace App\File;

use App\Parser\Parser;

class CascadeCommandFile
{
    public const KEY_COMMANDS = 'commands';

    private array $parsedValues;

    private array $commands;

    public function __construct(private Parser $parser)
    {
        $this->parsedValues = $this->parser->parse();
        $this->commands = $this->parsedValues[self::KEY_COMMANDS];
    }

    public function getCommands() : array
    {
        return $this->commands;
    }

    public function getCommandName(int|string $index) : string
    {
        return array_key_first($this->commands[$index]);
    }

    public function getCommandArguments(int|string $index) : array
    {
        $commandName = $this->getCommandName($index);

        return $this->commands[$index][$commandName];
    }
}
