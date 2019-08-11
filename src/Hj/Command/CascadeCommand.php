<?php

namespace Hj\Command;

use Hj\Exception\ArrayKeyNotExist;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

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

    private function getArrayValueFromKey($array, $keyName, $message)
    {
        if (!isset($array[$keyName])) {
            throw new ArrayKeyNotExist($message);
        }

        return $array[$keyName];
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = $input->getArgument('configFile');
        $value = Yaml::parseFile($configFile);

        try {
            $commands = $this->getArrayValueFromKey(
                $value,
                'commands',
                "The Yaml config file : '" . $configFile . "' must start with the key : 'commands'. Please configure it correctly."
            );
            foreach ($commands as $name => $items) {
                $arguments = $this->getArrayValueFromKey(
                    $items,
                    'arguments',
                    "The command : '" . $name . "' dont' have the key : 'arguments'. Please define it correctly."
                    );
                if (!is_array($arguments)) {
                    throw new \Exception("The 'arguments' value must be an array. For example : {jqlPath: \"jqls/assignee.yaml\", assignee: \"admin\", ids: \"3\"}");
                }
                $currentCommand = $this->getApplication()->find($name);

                $this->runCommand($currentCommand, $arguments, $output);
            }
        } catch (ArrayKeyNotExist $e) {
            $this->logger->error($e->getMessage());
        }
    }

    private function runCommand(Command $command, $arguments, $output)
    {
        $greetInput = new ArrayInput($arguments);
        $command->run($greetInput, $output);
    }
}