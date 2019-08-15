<?php

namespace Hj\File;

use Hj\Parser\Parser;

class CascadeCommandFile
{
    const KEY_COMMANDS = 'commands';
    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var array
     */
    private $parsedValues;

    /**
     * @var array
     */
    private $commands;

    /**
     * JqlFile constructor.
     * @param Parser $parser
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
        $this->parsedValues = $this->parser->parse();
        $this->commands = $this->parsedValues[self::KEY_COMMANDS];
    }

    /**
     * @return array
     */
    public function getCommands() : array
    {
        return $this->commands;
    }

    /**
     * @param $index
     * @return string
     */
    public function getCommandName($index) : string
    {
        return array_key_first($this->commands[$index]);
    }

    /**
     * @param $index
     * @return array
     */
    public function getCommandArguments($index) : array
    {
        $commandName = $this->getCommandName($index);

        return $this->commands[$index][$commandName];
    }

}