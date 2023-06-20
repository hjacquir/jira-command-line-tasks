<?php

declare(strict_types=1);

namespace App\Command;

use App\Action\ActionCollection;
use App\Action\ChangeAssignee;
use App\Condition\AlwaysTrue;
use App\Condition\Condition;
use Symfony\Component\Console\Input\InputArgument;

class AssigneeCommand extends AbstractCommand
{
    private const ACCOUNT_ID = 'accountId';

    protected function beforeProcess()
    {
        // TODO: Implement beforeProcess() method.
    }

    protected function afterProcess()
    {
        // TODO: Implement afterProcess() method.
    }

    protected function getCommandArguments(): array
    {
        return [
            [
                self::KEY_NAME => self::ACCOUNT_ID,
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => 'Assignee account id (string)',
            ],
            [
                self::KEY_NAME => self::ARG_IDS,
                self::KEY_MODE => InputArgument::OPTIONAL,
                self::KEY_DESC => self::ARG_IDS_DESC,
            ],
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
        $action = new ChangeAssignee(
            $this->getService(),
            $this->getInput()->getArgument(self::ACCOUNT_ID),
            $this->getLogger()
        );
        $collection = new ActionCollection();
        $collection->addAction($action);

        return $collection;
    }

    protected function getContentForConditionToMoveToNextTicket(): string
    {
        return '';
    }

    protected function getCommandName(): string
    {
        return 'jira:assignee-update';
    }
}
