<?php

namespace Hj\Command;

use Hj\Action\ActionCollection;
use Hj\Action\AddComment;
use Hj\Condition\AlwaysTrue;
use Hj\Exception\EmptyStringException;
use Hj\Exception\FileNotFoundException;
use Hj\Helper\CommentFormatter;
use JiraRestApi\Issue\Comment;
use Symfony\Component\Console\Input\InputArgument;

class CommentCommand extends AbstractCommand
{

    const ARG_COMMENT_FILE_PATH = 'commentFilePath';
    const ARG_IDS = 'ids';

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
        // TODO: Implement getCommandArguments() method.
        return [
            [
                self::KEY_NAME => self::ARG_COMMENT_FILE_PATH,
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => 'The path to the php file that load the comment',
            ],
            [
                self::KEY_NAME => self::ARG_IDS,
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
     * @return \Hj\Condition\Condition
     */
    protected function getCondition(): \Hj\Condition\Condition
    {
        return new AlwaysTrue();
    }

    /**
     * @return ActionCollection
     * @throws EmptyStringException
     * @throws FileNotFoundException
     */
    protected function getActionCollection(): ActionCollection
    {
        $commentFilePath = $this->getInput()->getArgument(self::ARG_COMMENT_FILE_PATH);
        $comment = new Comment();
        $commentFormatter = new CommentFormatter($commentFilePath);
        $body = $commentFormatter->getComment();
        $comment->setBody($body);
        $action = new AddComment($this->getService(), $comment, $this->getLogger());
        $collection = new ActionCollection();
        $collection->addAction($action);

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
        return 'comment:add';
    }
}