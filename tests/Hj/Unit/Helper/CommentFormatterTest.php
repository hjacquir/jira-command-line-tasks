<?php

namespace Hj\Tests\Unit\Helper;

use Hj\Exception\EmptyStringException;
use Hj\Exception\FileNotFoundException;
use Hj\Helper\CommentFormatter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Hj\Helper\CommentFormatter
 */
class CommentFormatterTest extends TestCase
{
    public function testThrowExceptionWhenFileDoesNotExist()
    {
        $this->expectException(FileNotFoundException::class);

        new CommentFormatter("bla");
    }

    public function testThrowExceptionWhenCommentIncludedFileDoesNotReturnString()
    {
        $this->expectException(EmptyStringException::class);

        new CommentFormatter(__DIR__ . '/filesFixture/emptyFile.php');
    }

    public function testGetCommentWillReturnString()
    {
        $formatter = new CommentFormatter(__DIR__ . '/filesFixture/comment.php');

        $this->assertSame('bla', $formatter->getComment());
    }
}
