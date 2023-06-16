<?php

declare(strict_types=1);

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\ChangeAssignee;
use Hj\Condition\AlwaysTrue;
use Hj\Condition\Condition;
use Symfony\Component\Console\Input\InputArgument;

class AssigneeCommand extends AbstractCommand
{
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
                self::KEY_NAME => 'assignee',
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => 'Assignee name (string)',
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
            $this->getInput()->getArgument('assignee'),
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
        return 'assignee:update';
    }
}
