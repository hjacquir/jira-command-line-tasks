<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\ChangeAssignee;
use Hj\Action\UpdateDueDate;
use Hj\Condition\AlwaysTrue;
use Hj\Jql\Condition;
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

class UpdateDueDateCommand extends Command
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
     * AssigneeCommand constructor.
     * @param string $yamlFile
     * @param Logger $logger
     */
    public function __construct($yamlFile, Logger $logger)
    {
        parent::__construct();
        $this->yamlFile = $yamlFile;
        $this->logger = $logger;
    }


    protected function configure()
    {
        $this->setName('issue:update-due-date');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $sr = new IssueService();
            $condition = new AlwaysTrue();
            // date format = 'YYYY-mm-dd'
            $mappingDueDate = [
'issueKey1' => 'dueDate1',
'issueKey2' => 'dueDate2',
'issueKey3' => 'dueDate3',
            ];
            $action = new UpdateDueDate($sr, $mappingDueDate, $this->logger);
            $collection = new ActionCollection();
            $collection->addAction($action);

            $jql = new Jql([]);
            $configurator = new JqlConfigurator($jql);
            $jql = $configurator->configure($this->yamlFile);

            $conditionMoveToNextTicket = new Condition('');
            $jqlLoader = new JqlBasedLoader($sr, $jql, 100, $conditionMoveToNextTicket);
            $processor = new Processor($sr, $condition, $collection, $jqlLoader);
            $processor->process();
        } catch (JiraException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}