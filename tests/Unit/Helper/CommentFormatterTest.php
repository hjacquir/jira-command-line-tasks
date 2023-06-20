<?php

declare(strict_types=1);

namespace App\Tests\Unit\Helper;

use App\Exception\EmptyStringExceptionInterface;
use App\Exception\FileNotFoundExceptionInterface;
use App\Helper\CommentFormatter;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Helper\CommentFormatter
 */
class CommentFormatterTest extends TestCase
{
    public function testThrowExceptionWhenFileDoesNotExist()
    {
        $this->expectException(FileNotFoundExceptionInterface::class);

        new CommentFormatter("bla");
    }

    public function testThrowExceptionWhenCommentIncludedFileDoesNotReturnString()
    {
        $this->expectException(EmptyStringExceptionInterface::class);

        new CommentFormatter(__DIR__ . '/filesFixture/emptyFile.php');
    }

    public function testGetCommentWillReturnString()
    {
        $formatter = new CommentFormatter(__DIR__ . '/filesFixture/comment.php');

        $this->assertSame('bla', $formatter->getComment());
    }
}
