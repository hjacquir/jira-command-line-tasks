<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\UpdateDueDate;
use Hj\Condition\AlwaysTrue;

class UpdateDueDateCommand extends AbstractCommand
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
        return [];
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
        // date format = 'YYYY-mm-dd'
        $mappingDueDate = [
            'PPDC-3' => '2019-12-12',
        ];
        $action = new UpdateDueDate($this->getService(), $mappingDueDate, $this->getLogger());
        $collection = new ActionCollection();
        $collection->addAction($action);

        return $collection;
    }

    /**
     * @return array
     */
    protected function getTicketsIds(): array
    {
        return [];
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
        return 'issue:update-due-date';
    }
}