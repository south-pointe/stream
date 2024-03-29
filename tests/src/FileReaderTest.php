<?php declare(strict_types=1);

namespace Tests\SouthPointe\Stream;

use ErrorException;
use SouthPointe\Stream\FileReader;

class FileReaderTest extends TestCase
{
    public function test_construct(): void
    {
        $stream = new FileReader('tests/samples/read.txt');
        self::assertFalse($stream->isEof());
        self::assertTrue($stream->isOpen());
        self::assertSame('rb', $stream->getMode());
    }

    public function test_with_no_such_file(): void
    {
        $file = 'tests/samples/invalid.txt';
        $this->expectException(ErrorException::class);
        $this->expectExceptionMessage("fopen({$file}): Failed to open stream: No such file or directory");
        new FileReader($file);
    }
}
