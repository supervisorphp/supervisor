<?php

declare(strict_types=1);

namespace Supervisor;

interface TailLogInterface
{
    public function getBytes(): string;

    public function getOffset(): int;

    /**
     * True when the log output was truncated due to length; fetch again from getOffset() to get more.
     */
    public function isOverflow(): bool;
}
