<?php

declare(strict_types=1);

namespace Supervisor;

final class TailLog implements TailLogInterface
{
    public function __construct(
        private readonly string $bytes,
        private readonly int $offset,
        private readonly bool $overflow,
    ) {}

    public static function fromTailLog(array $data): self
    {
        return new self(
            bytes: $data[0],
            offset: $data[1],
            overflow: (bool) $data[2],
        );
    }

    public function getBytes(): string
    {
        return $this->bytes;
    }

    public function getOffset(): int
    {
        return $this->offset;
    }

    public function isOverflow(): bool
    {
        return $this->overflow;
    }
}
