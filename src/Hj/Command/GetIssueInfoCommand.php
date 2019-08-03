<?php

namespace Hj\Command;

use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Common\Type;
use Box\Spout\Reader\ReaderFactory;
use Box\Spout\Writer\WriterFactory;
use Hj\Action\ActionCollection;
use Hj\Action\CollectFieldValue;
use Hj\Condition\AlwaysTrue;
use Hj\FieldValue\Assignee\Name as AssigneeName;
use Hj\FieldValue\Date\Created\StringValue;
use Hj\FieldValue\Key;
use Hj\FieldValue\Status\Name;
use Hj\FieldValue\Summary;
use Hj\Jql\Condition;
use Hj\Jql\Jql;
use Hj\JqlConfigurator;
use Hj\Loader\JqlBasedLoader;
use Hj\Processor\Processor;
use Hj\Recorder\XlsxRecorder;
use JiraRestApi\Issue\IssueService;
use JiraRestApi\JiraException;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Allows to export in an excel file the set of tickets corresponding to the JQL
 * Class GetIssueInfoCommand
 * @package Hj\Command
 */
class GetIssueInfoCommand extends Command
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
     * GetIssueInfoCommand constructor.
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
        $this->setName('issue:get-fields');
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
     * @throws UnsupportedTypeException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $ids = $input->getArgument('ids');

        try {
            $sr = new IssueService();
            $condition = new AlwaysTrue();
            $statusName = new Name();
            $createdDateString = new StringValue('d/m/Y');
            $assigneeName = new AssigneeName();
            $action = new CollectFieldValue(
                [
                    new Key(),
                    $statusName,
                    $createdDateString,
                    $assigneeName,
                    new Summary(),
                ]
            );
            $collection = new ActionCollection();
            $collection->addAction($action);

            $jql = new Jql($ids);
            $configurator = new JqlConfigurator($jql);
            $jql = $configurator->configure($this->yamlFile);

            $conditionMoveToNextTicket = new Condition('');
            $jqlLoader = new JqlBasedLoader($sr, $jql, 100, $conditionMoveToNextTicket);
            $processor = new Processor($sr, $condition, $collection, $jqlLoader);
            $processor->process();
            $issueFields = $action->getCollectedValues();

            $reader = ReaderFactory::create(Type::XLSX);
            $writer = WriterFactory::create(Type::XLSX);
            $recorder = new XlsxRecorder($reader, $writer);
            $recorder->save('xlsx/issues.xlsx', 'xlsx/temp.xlsx', 'Feuil1', $issueFields);

            $this->logger->info((string) $jql);
            $this->logger->info('The data has been saved.');
        } catch (JiraException $e) {
            echo $e->getMessage() . PHP_EOL;
        }
    }
}