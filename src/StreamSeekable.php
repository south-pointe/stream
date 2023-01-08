<?php declare(strict_types=1);

namespace SouthPointe\Stream;

interface StreamSeekable
{
    /**
     * @return int
     */
    function currentPosition(): int;

    /**
     * @return void
     */
    function rewind(): void;

    /**
     * @param int $offset
     * @param int $whence
     * @return int
     */
    function seek(int $offset, int $whence = SEEK_SET): int;
}