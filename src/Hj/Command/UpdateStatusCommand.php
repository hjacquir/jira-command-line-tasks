<?php

declare(strict_types=1);

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\ChangeIssueStatus;
use Hj\Condition\AlwaysTrue;
use Hj\Condition\Condition;
use JiraRestApi\Issue\Transition;
use Symfony\Component\Console\Input\InputArgument;

class UpdateStatusCommand extends AbstractCommand
{
    private const ARG_NEW_STATUS = 'status';

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
                self::KEY_NAME => self::ARG_NEW_STATUS,
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => 'The new status',
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
        $changeStatusAction = new ChangeIssueStatus($this->getService(), $this->getLogger());
        $transition = new Transition();
        $transition->setTransitionName($this->getInput()->getArgument(self::ARG_NEW_STATUS));
        $changeStatusAction->setTransition($transition);
        $collection = new ActionCollection();
        $collection->addAction($changeStatusAction);

        return $collection;
    }

    protected function getContentForConditionToMoveToNextTicket(): string
    {
        return '';
    }

    protected function getCommandName(): string
    {
        return 'issue:update-status';
    }
}
