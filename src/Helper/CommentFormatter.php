<?php

declare(strict_types=1);

namespace App\Helper;

use App\Exception\EmptyStringExceptionInterface;
use App\Exception\FileNotFoundExceptionInterface;

class CommentFormatter
{
    private string $comment = '';

    public function __construct(private string $commentFilePath)
    {
        if (false === file_exists($commentFilePath)) {
            throw new FileNotFoundExceptionInterface("The file '" . $this->commentFilePath . "' does not exist. Please create it and customize your comment.");
        }

        $loadedComment = include $this->commentFilePath;

        if (1 === $loadedComment || '' === $loadedComment) {
            throw new EmptyStringExceptionInterface("Your comment file must return a string and it must not be empty.");
        }

        $this->comment = $loadedComment;
    }

    public function getComment(): string
    {
        return $this->comment;
    }
}
