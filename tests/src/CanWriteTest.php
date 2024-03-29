<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use SouthPointe\Stream\Exceptions\StreamErrorException;
use SouthPointe\Stream\FileStream;
use SouthPointe\Stream\FileWriter;
use SouthPointe\Stream\MemoryStream;
use TypeError;

class CanWriteTest extends TestCase
{
    public function test_write(): void
    {
        $file = 'tests/samples/write.txt';
        $stream = new FileStream($file);
        $stream->write('abc');
        $stream->rewind();
        self::assertSame('abc', $stream->read(5));
    }

    public function test_truncate_without_args(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->truncate();

        self::assertSame('', $stream->readToEnd());
    }

    public function test_truncate_with_size(): void
    {
        $stream = new MemoryStream();
        $stream->write('abc');
        $stream->truncate(1)->rewind();

        self::assertSame('a', $stream->readToEnd());
    }

    public function test_isOpen(): void
    {
        $stream = new FileWriter('tests/samples/write.txt');
        self::assertTrue($stream->isOpen());
        self::assertTrue($stream->close());
        self::assertFalse($stream->isOpen());
    }

    public function test_isClosed(): void
    {
        $stream = new FileWriter('tests/samples/write.txt');
        self::assertFalse($stream->isClosed());
        self::assertTrue($stream->close());
        self::assertTrue($stream->isClosed());
    }

    public function test_close(): void
    {
        $stream = new FileWriter('tests/samples/write.txt');
        self::assertTrue($stream->close());
    }

    public function test_write_after_close(): void
    {
        $path = 'tests/samples/write.txt';
        $this->expectExceptionMessage('fwrite(): supplied resource is not a valid stream resource');
        $this->expectException(TypeError::class);
        $stream = new FileWriter($path);
        $stream->close();
        $stream->write('def');
    }

    public function test_write_on_non_writable(): void
    {
        $this->expectExceptionMessage('fwrite(): Write of 3 bytes failed with errno=9 Bad file descriptor');
        $this->expectException(StreamErrorException::class);
        $stream = new FileStream('tests/samples/read.txt', 'r');
        $stream->write('abc');
    }
}
