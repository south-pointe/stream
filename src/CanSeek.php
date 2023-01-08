<?php declare(strict_types=1);

namespace SouthPointe\Stream;

use function fseek;
use function ftell;
use function rewind;
use const SEEK_SET;

trait CanSeek
{
    use ThrowsError;

    /**
     * @return resource
     */
    abstract protected function getStream(): mixed;

    /**
     * @return int
     */
    function currentPosition(): int
    {
        $position = @ftell($this->getStream());
        if ($position === false) {
            $this->throwLastError();
        }
        return $position;
    }

    /**
     * @return void
     */
    function rewind(): void
    {
        $result = @rewind($this->getStream());
        if ($result === false) {
            $this->throwLastError();
        }
    }

    /**
     * @param int $offset
     * @param int $whence
     * @return void
     */
    function seek(int $offset, int $whence = SEEK_SET): void
    {
        $result = @fseek($this->getStream(), $offset, $whence);
        if ($result === -1) {
            $this->throwLastError();
        }
    }
}