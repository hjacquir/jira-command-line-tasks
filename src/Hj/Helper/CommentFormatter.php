<?php

declare(strict_types=1);

namespace Hj\Helper;

use Hj\Exception\EmptyStringException;
use Hj\Exception\FileNotFoundException;

class CommentFormatter
{
    private string $comment = '';

    public function __construct(private string $commentFilePath)
    {
        if (false === file_exists($commentFilePath)) {
            throw new FileNotFoundException("The file '" . $this->commentFilePath . "' does not exist. Please create it and customize your comment.");
        }

        $loadedComment = include $this->commentFilePath;

        if (1 === $loadedComment || '' === $loadedComment) {
            throw new EmptyStringException("Your comment file must return a string and it must not be empty.");
        }

        $this->comment = $loadedComment;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}
