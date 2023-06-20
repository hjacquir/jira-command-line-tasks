<?php

declare(strict_types=1);

namespace App\Command;

use App\Action\ActionCollection;
use App\Condition\Condition;
use App\File\JqlFile;
use App\Jql\Condition as JqlConditions;
use App\Jql\Jql;
use App\JqlBuilder;
use App\Loader\JqlBasedLoader;
use App\Parser\YamlParser;
use App\Processor;
use JiraRestApi\Issue\IssueService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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

    private InputInterface $input;

    private OutputInterface $output;

    public function __construct(
        private LoggerInterface $logger,
        private IssueService $service
    ) {
        parent::__construct();
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getService() : IssueService
    {
        return $this->service;
    }

    protected function configure(): void
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

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->input = $input;
        $this->output = $output;

        try {
            $issueIdsAsString = $this->getIssueIdsAsString();
            $contentForConditionMoveToNextTicket = $this->getContentForConditionToMoveToNextTicket();
            $condition = $this->getCondition();
            $collectionAction = $this->getActionCollection();
            $yamlFilePath = $this->getInput()->getArgument(self::ARG_JQL_PATH);

            $jql = new Jql($issueIdsAsString);
            $jqlFile = new JqlFile(
                new YamlParser(
                    $yamlFilePath,
                    new \App\Validator\YamlFile\KeyValueValidator\Jql($yamlFilePath)
                )
            );
            $jqlBuilder = new JqlBuilder($jql, $jqlFile);
            $jql = $jqlBuilder->build();
            $conditionMoveToNextTicket = new JqlConditions($contentForConditionMoveToNextTicket);

            $jqlLoader = new JqlBasedLoader($this->service, $jql, 100, $conditionMoveToNextTicket);
            $processor = new Processor($condition, $collectionAction, $jqlLoader);
            $this->beforeProcess();
            $processor->process();
            $this->afterProcess();
            $this->getLogger()->info((string) $jql);
        } catch (\Throwable $e) {
            echo $e->getMessage() . PHP_EOL;

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    public function getInput(): InputInterface
    {
        return $this->input;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    protected abstract function beforeProcess();

    protected abstract function afterProcess();

    protected abstract function getCommandArguments() : array;

    protected abstract function getCommandOptions() : array;

    protected abstract function getCondition() : Condition;

    protected abstract function getActionCollection() : ActionCollection;

    protected abstract function getContentForConditionToMoveToNextTicket() : string;

    protected abstract function getCommandName() : string ;

    private function getValueDefaultEmptyString(array $values, string $name)
    {
        return $values[$name] ?? '';
    }

    private function getValueDefaultNull(array $values, string $name): mixed
    {
        return $values[$name] ?? null;
    }

    private function getIssueIdsAsString(): string
    {
        return $this->getInput()->getArgument('ids') ?? '';
    }
}
