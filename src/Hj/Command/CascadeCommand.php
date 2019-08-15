<?php

namespace Hj\Command;

use Hj\File\CascadeCommandFile;
use Hj\Parser\YamlParser;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CascadeCommand extends Command
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * CascadeCommand constructor.
     * @param Logger $logger
     */
    public function __construct(Logger $logger)
    {
        parent::__construct();
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('cascade:command');

        $this
            ->addArgument(
                'configFile',
                InputArgument::REQUIRED,
                'The yaml config file'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $yamlFilePath = $input->getArgument('configFile');

        $cascadeCommandFile = new CascadeCommandFile(
            new YamlParser(
                $yamlFilePath,
                new \Hj\Validator\YamlFile\KeyValueValidator\CascadeCommand($yamlFilePath)
            )
        );

        foreach ($cascadeCommandFile->getCommands() as $index => $command) {
            $commandName = $cascadeCommandFile->getCommandName($index);
            $this->runCommand($this->getApplication()->find($commandName), $cascadeCommandFile->getCommandArguments($index), $output);
        }
    }

    /**
     * @param Command $command
     * @param $arguments
     * @param $output
     * @throws \Exception
     */
    private function runCommand(Command $command, $arguments, $output)
    {
        $greetInput = new ArrayInput($arguments);
        $command->run($greetInput, $output);
    }
}