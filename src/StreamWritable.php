<?php declare(strict_types=1);

namespace SouthPointe\Stream;

interface StreamWritable
{
    /**
     * @param string $data
     * @param int<0, max>|null $length
     * @return int
     */
    function write(string $data, ?int $length = null): int;

    /**
     * @param bool $blocking
     * @return void
     */
    function exclusiveLock(bool $blocking = true): void;

    /**
     * @return void
     */
    function unlock(): void;
}