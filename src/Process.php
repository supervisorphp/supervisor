<?php

namespace Supervisor;

use ReturnTypeWillChange;

/**
 * Process object holding data for a single process.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Buster Neece <buster@busterneece.com>
 */
final class Process implements ProcessInterface
{
    private array $payload;

    public function __construct(array $payload = [])
    {
        $this->payload = $payload;
    }

    /**
     * @inheritDoc
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->payload['name'];
    }

    /**
     * @inheritDoc
     */
    public function isRunning(): bool
    {
        return $this->checkState(self::RUNNING);
    }

    /**
     * @inheritDoc
     */
    public function checkState(int $state): bool
    {
        return $this->payload['state'] === $state;
    }

    /**
     * @inheritDoc
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * @inheritDoc
     */
    public function offsetGet($offset): mixed
    {
        return $this->payload[$offset] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function offsetExists($offset): bool
    {
        return isset($this->payload[$offset]);
    }

    /**
     * @inheritDoc
     */
    public function offsetSet($offset, $value): void
    {
        throw new \LogicException('Process object cannot be altered');
    }

    /**
     * @inheritDoc
     */
    public function offsetUnset($offset): void
    {
        throw new \LogicException('Process object cannot be altered');
    }
}
