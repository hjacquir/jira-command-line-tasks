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
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class BaseCommand
 * @package Hj\Command
 */
abstract class AbstractCommand extends Command
{
    const KEY_NAME = 'name';
    const KEY_MODE = 'mode';
    const KEY_DESC = 'description';
    const KEY_SHORT = 'shortcut';
    const KEY_DEFAULT = 'default';
    const ARG_IDS = 'ids';
    const ARG_IDS_DESC = 'Issue Ids (integer list separated by comma. E.g : 12,34)';
    const ARG_JQL_PATH = 'jqlPath';

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
     * @param Logger $logger
     * @param IssueService $service
     */
    public function __construct(Logger $logger, IssueService $service)
    {
        parent::__construct();
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
        $this->addArgument(
            self::ARG_JQL_PATH,
            InputArgument::REQUIRED,
            'Jql file path'
        );
        foreach ($this->getCommandArguments() as $commandArgument) {
            $this->addArgument(
               $commandArgument[self::KEY_NAME],
               $this->getValueDefaultNull($commandArgument, self::KEY_MODE),
               $this->getValueDefaultEmptyString($commandArgument, self::KEY_DESC),
               $this->getValueDefaultNull($commandArgument, self::KEY_DEFAULT)
            );
        }
        foreach ($this->getCommandOptions() as $commandOption) {
            $this->addOption(
                $commandOption[self::KEY_NAME],
                $this->getValueDefaultNull($commandOption, self::KEY_SHORT),
                $this->getValueDefaultNull($commandOption, self::KEY_MODE),
                $this->getValueDefaultEmptyString($commandOption, self::KEY_DESC),
                $this->getValueDefaultNull($commandOption, self::KEY_DEFAULT)
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
            $ticketsId = $this->getIssueIdsAsString();
            $contentForConditionMoveToNextTicket = $this->getContentForConditionToMoveToNextTicket();
            $condition = $this->getCondition();
            $collectionAction = $this->getActionCollection();

            $jql = new Jql($ticketsId);
            $configurator = new JqlConfigurator($jql);
            $jql = $configurator->configure($this->getInput()->getArgument(self::ARG_JQL_PATH));
            $conditionMoveToNextTicket = new JqlCondiftion($contentForConditionMoveToNextTicket);

            $jqlLoader = new JqlBasedLoader($this->service, $jql, 100, $conditionMoveToNextTicket);
            $processor = new Processor($this->service, $condition, $collectionAction, $jqlLoader);
            $this->beforeProcess();
            $processor->process();
            $this->afterProcess();
            $this->getLogger()->info((string) $jql);
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

    /**
     * @return string
     */
    private function getIssueIdsAsString(): string
    {
        return $this->getInput()->getArgument('ids') ?? '';
    }
}