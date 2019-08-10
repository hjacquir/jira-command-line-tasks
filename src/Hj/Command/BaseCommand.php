<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Condition\Condition;
use Hj\Jql\Condition as JqlCondiftion;
use Hj\Jql\Jql;
use Hj\JqlConfigurator;
use Hj\Loader\JqlBasedLoader;
use Hj\Processor\Processor;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BaseCommand
 * @package Hj\Command
 */
abstract class BaseCommand extends Command
{
    /**
     * @var string
     */
    private $yamlFile;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var IssueService
     */
    private $service;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * BaseCommand constructor.
     * @param string $yamlFile
     * @param Logger $logger
     * @param IssueService $service
     */
    public function __construct(string $yamlFile, Logger $logger, IssueService $service)
    {
        parent::__construct();
        $this->yamlFile = $yamlFile;
        $this->logger = $logger;
        $this->service = $service;
    }

    /**
     * @return Logger
     */
    public function getLogger(): Logger
    {
        return $this->logger;
    }

    /**
     * @return IssueService
     */
    public function getService() : IssueService
    {
        return $this->service;
    }

    protected function configure()
    {
        parent::configure();
        $this->setName($this->getCommandName());
        foreach ($this->getCommandArguments() as $commandArgument) {
            $this->addArgument(
               $commandArgument['name'],
               $this->getValueDefaultNull($commandArgument, 'mode'),
               $this->getValueDefaultEmptyString($commandArgument, 'description'),
               $this->getValueDefaultNull($commandArgument, 'default')
            );
        }
        foreach ($this->getCommandOptions() as $commandOption) {
            $this->addOption(
                $commandOption['name'],
                $this->getValueDefaultNull($commandOption, 'shortcut'),
                $this->getValueDefaultNull($commandOption, 'mode'),
                $this->getValueDefaultEmptyString($commandOption, 'description'),
                $this->getValueDefaultNull($commandOption, 'default')
            );
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void|null
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        try {
            $ticketsId = $this->getTicketsIds();
            $contentForConditionMoveToNextTicket = $this->getContentForConditionToMoveToNextTicket();
            $condition = $this->getCondition();
            $collectionAction = $this->getActionCollection();

            $jql = new Jql($ticketsId);
            $configurator = new JqlConfigurator($jql);
            $jql = $configurator->configure($this->yamlFile);
            $conditionMoveToNextTicket = new JqlCondiftion($contentForConditionMoveToNextTicket);

            $jqlLoader = new JqlBasedLoader($this->service, $jql, 100, $conditionMoveToNextTicket);
            $processor = new Processor($this->service, $condition, $collectionAction, $jqlLoader);
            $this->beforeProcess();
            $processor->process();
            $this->afterProcess();
        } catch (JiraException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }

    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    protected abstract function beforeProcess();

    protected abstract function afterProcess();

    /**
     * @return array
     */
    protected abstract function getCommandArguments() : array;

    /**
     * @return array
     */
    protected abstract function getCommandOptions() : array;

    /**
     * @return Condition
     */
    protected abstract function getCondition() : Condition;

    /**
     * @return ActionCollection
     */
    protected abstract function getActionCollection() : ActionCollection;

    /**
     * @return array
     */
    protected abstract function getTicketsIds() : array;

    /**
     * @return string
     */
    protected abstract function getContentForConditionToMoveToNextTicket() : string;

    /**
     * @return string
     */
    protected abstract function getCommandName() : string ;

    /**
     * @param array $values
     * @param string $name
     * @return mixed|string
     */
    private function getValueDefaultEmptyString(array $values, string $name)
    {
        return $values[$name] ?? '';
    }

    /**
     * @param array $values
     * @param string $name
     * @return mixed|null
     */
    private function getValueDefaultNull(array $values, string $name)
    {
        return $values[$name] ?? null;
    }
}