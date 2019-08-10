<?php

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

    /**
     * @return array
     */
    protected function getCommandArguments(): array
    {
        return [
            [
                self::KEY_NAME => 'assignee',
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => 'Assignee name (string)',
            ],
            [
                self::KEY_NAME => 'ids',
                self::KEY_MODE => InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                self::KEY_DESC => 'Issue Ids (integer list)',
            ],
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
     * @return Condition
     */
    protected function getCondition(): Condition
    {
        return new AlwaysTrue();
    }

    /**
     * @return ActionCollection
     */
    protected function getActionCollection(): ActionCollection
    {
        $action = new ChangeAssignee($this->getService(), $this->getInput()->getArgument('assignee'), $this->getLogger());
        $collection = new ActionCollection();
        $collection->addAction($action);

        return $collection;
    }

    /**
     * @return array
     */
    protected function getTicketsIds(): array
    {
        return $this->getInput()->getArgument('ids');
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
        return 'assignee:update';
    }
}