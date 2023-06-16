<?php

declare(strict_types=1);

namespace Hj\Command;

use Box\Spout\Common\Type;
use Box\Spout\Reader\Common\Creator\ReaderFactory;
use Box\Spout\Writer\Common\Creator\WriterFactory;
use Hj\Action\ActionCollection;
use Hj\Action\CollectFieldValue;
use Hj\Condition\AlwaysTrue;
use Hj\Condition\Condition;
use Hj\FieldValue\Assignee\Name as AssigneeName;
use Hj\FieldValue\Date\Created\StringValue;
use Hj\FieldValue\Key;
use Hj\FieldValue\Status\Name;
use Hj\FieldValue\Summary;
use Hj\XlsxRecorder;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Allows to export in an excel file the set of tickets corresponding to the JQL
 */
class GetIssueInfoCommand extends AbstractCommand
{
    private CollectFieldValue $action;

    protected function beforeProcess()
    {
        // TODO: Implement beforeProcess() method.
    }

    protected function afterProcess()
    {
        $issueFields = $this->action->getCollectedValues();
        $reader = ReaderFactory::createFromType(Type::XLSX);
        $writer = WriterFactory::createFromType(Type::XLSX);
        $recorder = new XlsxRecorder($reader, $writer);

        $xlsxFileWhereToRecordData = 'xlsx/issues.xlsx';

        $recorder->save(
            $xlsxFileWhereToRecordData,
            'xlsx/temp.xlsx',
            'Feuil1',
            $issueFields,
            null
        );
        $this->getLogger()->info(sprintf('The data has been saved in the file : %s', $xlsxFileWhereToRecordData));
    }

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

    protected function getCommandOptions(): array
    {
        return [];
    }

    protected function getCondition(): Condition
    {
        return new AlwaysTrue();
    }

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

    protected function getContentForConditionToMoveToNextTicket(): string
    {
        return '';
    }

    protected function getCommandName(): string
    {
        return 'issue:get-fields';
    }
}
