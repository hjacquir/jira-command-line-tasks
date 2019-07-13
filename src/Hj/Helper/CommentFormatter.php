<?php

namespace Hj\Helper;

use Hj\Exception\EmptyStringException;
use Hj\Exception\FileNotFoundException;

class CommentFormatter
{
    /**
     * @var string
     */
    private $comment;

    /**
     * CommentFormatter constructor.
     */
    public function __construct($commentFilePath)
    {
        if (!file_exists($commentFilePath)) {
            throw new FileNotFoundException("The file '" . $commentFilePath . "' does not exist. Please create it and customize your comment.");
        }
        $loadedComment = include $commentFilePath;
        if ($loadedComment == 1 || $loadedComment == '') {
            throw new EmptyStringException("Your comment file must return a string and it must not be empty.");
        }
        $this->comment = $loadedComment;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }
}