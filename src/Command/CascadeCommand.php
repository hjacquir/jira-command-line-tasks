<?php

declare(strict_types=1);

namespace App\Command;

use App\File\CascadeCommandFile;
use App\Parser\YamlParser;
use App\Validator\YamlFile\KeyValueValidator\CascadeCommand as CascadeCommandValidator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CascadeCommand extends Command
{
    protected function configure()
    {
        $this->setName('jira:cascade-command');

        $this
            ->addArgument(
                'configFile',
                InputArgument::REQUIRED,
                'The yaml config file'
            );
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $yamlFilePath = $input->getArgument('configFile');

        $cascadeCommandFile = new CascadeCommandFile(
            new YamlParser(
                $yamlFilePath,
                new CascadeCommandValidator($yamlFilePath)
            )
        );

        foreach ($cascadeCommandFile->getCommands() as $index => $command) {
            $commandName = $cascadeCommandFile->getCommandName($index);
            $this->runCommand(
                $this->getApplication()->find($commandName),
                $cascadeCommandFile->getCommandArguments($index),
                $output
            );
        }

        return Command::SUCCESS;
    }

    private function runCommand(Command $command, $arguments, $output)
    {
        $greetInput = new ArrayInput($arguments);
        $command->run($greetInput, $output);
    }
}
