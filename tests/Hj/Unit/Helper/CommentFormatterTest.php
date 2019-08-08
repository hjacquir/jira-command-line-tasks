<?php

namespace Hj\Tests\Unit\Helper;

use Hj\Helper\CommentFormatter;
use PHPUnit\Framework\TestCase;

class CommentFormatterTest extends TestCase
{
    /**
     * @expectedException Hj\Exception\FileNotFoundException
     */
    public function testThrowExceptionWhenFileDoesNotExist()
    {
        new CommentFormatter("bla");
    }

    /**
     * @expectedException Hj\Exception\EmptyStringException
     */
    public function testThrowExceptionWhenCommentIncludedFileDoesNotReturnString()
    {
        new CommentFormatter(__DIR__ . '/filesFixture/emptyFile.php');
    }

    public function testGetCommentWillReturnString()
    {
        $formatter = new CommentFormatter(__DIR__ . '/filesFixture/comment.php');
        $this->assertSame('bla', $formatter->getComment());
    }

}