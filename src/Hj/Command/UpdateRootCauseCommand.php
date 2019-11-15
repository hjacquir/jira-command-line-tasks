<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\UpdateRootCause;
use Hj\Condition\AlwaysTrue;
use Symfony\Component\Console\Input\InputArgument;

class UpdateRootCauseCommand extends AbstractCommand
{
    const KEY_NAME_ROOT_CAUSE_CATEGORY = 'rootCauseCategory';
    const KEY_NAME_ROOT_CAUSE_COMMENT = 'rootCauseComment';

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
                self::KEY_NAME => self::KEY_NAME_ROOT_CAUSE_CATEGORY,
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => 'Root cause category value',
            ],
            [
                self::KEY_NAME => self::ARG_IDS,
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => self::ARG_IDS_DESC,
            ],
            [
                self::KEY_NAME => self::KEY_NAME_ROOT_CAUSE_COMMENT,
                self::KEY_MODE => InputArgument::OPTIONAL,
                self::KEY_DESC => 'Root cause comment value',
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
        $rootCauseCategory = $this->getInput()->getArgument(self::KEY_NAME_ROOT_CAUSE_CATEGORY);
        $rootCauseComment = $this->getInput()->getArgument(self::KEY_NAME_ROOT_CAUSE_COMMENT)  ?? '';
        $collection = new ActionCollection();
        $collection->addAction(
            new UpdateRootCause(
                $this->getService(),
                ['value' => $rootCauseCategory],
                $rootCauseComment,
                $this->getLogger())
        );

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
        return 'issue:update-root-cause';
    }
}