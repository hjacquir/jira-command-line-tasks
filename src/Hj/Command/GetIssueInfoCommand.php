<?php

namespace Hj\Command;

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
use Hj\XlsxRecorder;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Allows to export in an excel file the set of tickets corresponding to the JQL
 * Class GetIssueInfoCommand
 * @package Hj\Command
 */
class GetIssueInfoCommand extends AbstractCommand
{
    /**
     * @var CollectFieldValue
     */
    private $action;

    protected function beforeProcess()
    {
        // TODO: Implement beforeProcess() method.
    }

    protected function afterProcess()
    {
        $issueFields = $this->action->getCollectedValues();
        $reader = ReaderFactory::create(Type::XLSX);
        $writer = WriterFactory::create(Type::XLSX);
        $recorder = new XlsxRecorder($reader, $writer);
        $recorder->save('xlsx/issues.xlsx', 'xlsx/temp.xlsx', 'Feuil1', $issueFields);
        $this->getLogger()->info('The data has been saved.');
    }

    /**
     * @return array
     */
    protected function getCommandArguments(): array
    {
        return [
            [
                self::KEY_NAME => self::ARG_IDS,
                self::KEY_MODE => InputArgument::OPTIONAL,
                self::KEY_DESC => self::ARG_IDS_DESC,
            ]
        ];
    }

    /**
     * @return array
     */
    protected function getCommandOptions(): array
    {
        return [];
    }

    /**
     * @return \Hj\Condition\Condition
     */
    protected function getCondition(): \Hj\Condition\Condition
    {
        return new AlwaysTrue();
    }

    /**
     * @return ActionCollection
     */
    protected function getActionCollection(): ActionCollection
    {
        $statusName = new Name();
        $createdDateString = new StringValue('d/m/Y');
        $assigneeName = new AssigneeName();
        $this->action = new CollectFieldValue(
            [
                new Key(),
                $statusName,
                $createdDateString,
                $assigneeName,
                new Summary(),
            ]
        );
        $collection = new ActionCollection();
        $collection->addAction($this->action);

        return $collection;
    }

    /**
     * @return string
     */
    protected function getContentForConditionToMoveToNextTicket(): string
    {
        return '';
    }

    /**
     * @return string
     */
    protected function getCommandName(): string
    {
        return 'issue:get-fields';
    }
}