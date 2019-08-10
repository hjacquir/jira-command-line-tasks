<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\ChangeAssignee;
use Hj\Condition\AlwaysTrue;
use Hj\Condition\Condition;
use Symfony\Component\Console\Input\InputArgument;

class AssigneeCommand extends BaseCommand
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
                'name' => 'assignee',
                'mode' => InputArgument::REQUIRED,
                'description' => 'Assignee name (string)',
            ],
            [
                'name' => 'ids',
                'mode' => InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'description' => 'Issue Ids (integer list)',
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
        return 'change:assignee';
    }
}