<?php

declare(strict_types=1);

namespace App\Command;

use App\Action\ActionCollection;
use App\Action\AddComment;
use App\Condition\AlwaysTrue;
use App\Condition\Condition;
use App\Exception\EmptyStringExceptionInterface;
use App\Exception\FileNotFoundExceptionInterface;
use App\Helper\CommentFormatter;
use JiraRestApi\Issue\Comment;
use Symfony\Component\Console\Input\InputArgument;

class CommentCommand extends AbstractCommand
{
    private const ARG_COMMENT_FILE_PATH = 'commentFilePath';

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
                self::KEY_NAME => self::ARG_COMMENT_FILE_PATH,
                self::KEY_MODE => InputArgument::REQUIRED,
                self::KEY_DESC => 'The path to the php file that load the comment',
            ],
            [
                self::KEY_NAME => self::ARG_IDS,
                self::KEY_MODE => InputArgument::OPTIONAL,
                self::KEY_DESC => 'Issue Ids (integer list)',

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

    protected function getContentForConditionToMoveToNextTicket(): string
    {
        return '';
    }

    protected function getCommandName(): string
    {
        return 'jira:comment-add';
    }
}
