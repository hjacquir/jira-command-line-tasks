<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\ChangeAssigneeByReporter;
use Hj\Condition\AssigneeNotEqualToReporter;
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

class ChangeAssigneeByReporterCommand extends Command
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
     * ChangeAssigneeByReporterCommand constructor.
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
        $this->setName('assignee:change-by-reporter');
        $this
            ->addArgument(
                'ids',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'Issue Ids (integer list)'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $ids = $input->getArgument('ids');

        try {
            $sr = new IssueService();
            $condition = new AssigneeNotEqualToReporter();
            $action = new ChangeAssigneeByReporter($sr, $this->logger);
            $collection = new ActionCollection();
            $collection->addAction($action);

            $jql = new Jql($ids);
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