<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use Closure;
use function feof;
use function flock;
use function fread;
use const LOCK_NB;
use const LOCK_SH;

trait CanRead
{
    use CanUnlock;
    use ThrowsError;

    /**
     * @return resource
     */
    abstract protected function getStream(): mixed;

    /**
     * @param int<0, max> $length
     * @return string
     */
    public function read(int $length): string
    {
        $data = @fread($this->getStream(), $length);
        if ($data === false) {
            $this->throwLastError();
        }
        return $data;
    }

    /**
     * @param StreamWritable $writer
     * @return int
     */
    public function copyTo(StreamWritable $writer): int
    {
        $size = 0;
        while (!$this->isEof()) {
            $size += $writer->write($this->read(4096));
        }
        return $size;
    }

    /**
     * @return bool
     */
    public function isEof(): bool
    {
        return feof($this->getStream());
    }

    /**
     * @param bool $blocking
     * @return void
     */
    public function sharedLock(bool $blocking = true): void
    {
        $result = @flock(
            $this->stream,
            $blocking ? LOCK_SH : LOCK_SH | LOCK_NB
        );

        if ($result === false) {
            $this->throwLastError();
        }
    }

    /**
     * @template TReturn
     * @param Closure(static): TReturn $call
     * @return TReturn
     */
    public function withSharedLock(Closure $call): mixed
    {
        try {
            $this->sharedLock();
            return $call($this);
        }
        finally {
            $this->unlock();
        }
    }
}