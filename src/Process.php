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
    public function __construct(
        private readonly array $payload = []
    ) {
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
    public function getState(): ProcessStates
    {
        return ProcessStates::from($this->payload['state']);
    }

    /**
     * @inheritDoc
     */
    public function isRunning(): bool
    {
        return $this->checkState(ProcessStates::Running);
    }

    /**
     * @inheritDoc
     */
    public function checkState(int|ProcessStates $state): bool
    {
        if (is_int($state)) {
            $state = ProcessStates::tryFrom($state);
        }

        return $this->getState() === $state;
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
