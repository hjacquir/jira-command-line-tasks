<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\ChangeIssueStatus;
use Hj\Condition\AlwaysTrue;
use Hj\Condition\Condition;
use JiraRestApi\Issue\Transition;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class UpdateStatusCommand
 * @package Hj\Command
 */
class UpdateStatusCommand extends AbstractCommand
{

    const ARG_NEW_STATUS = 'status';

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
                self::KEY_NAME => self::ARG_NEW_STATUS,
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => 'The new status',
            ],
            [
                self::KEY_NAME => self::ARG_IDS,
                self::KEY_MODE => InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                self::KEY_DESC => self::ARG_IDS_DESC,
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
        $changeStatusAction = new ChangeIssueStatus($this->getService(), $this->getLogger());
        $transition = new Transition();
        $transition->setTransitionName($this->getInput()->getArgument(self::ARG_NEW_STATUS));
        $changeStatusAction->setTransition($transition);
        $collection = new ActionCollection();
        $collection->addAction($changeStatusAction);

        return $collection;
    }

    /**
     * @return array
     */
    protected function getTicketsIds(): array
    {
        return $this->getInput()->getArgument(self::ARG_IDS);
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
        return 'issue:update-status';
    }
}