<?php

namespace Supervisor;

/**
 * Process object holding data for a single process.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class Process implements \ArrayAccess
{
    /**
     * Process states.
     */
    public const STOPPED = 0;
    public const STARTING = 10;
    public const RUNNING = 20;
    public const BACKOFF = 30;
    public const STOPPING = 40;
    public const EXITED = 100;
    public const FATAL = 200;
    public const UNKNOWN = 1000;

    /**
     * Process info.
     *
     * @var array
     */
    protected $payload = [];

    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }

    /**
     * Returns the process info array.
     *
     * @return array
     */
    public function getPayload(): array
    {
        return $this->payload;
    }

    /**
     * Returns the process name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->payload['name'];
    }

    /**
     * Checks whether the process is running.
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->checkState(self::RUNNING);
    }

    /**
     * Checks if process is in the given state.
     *
     * @param int $state
     *
     * @return bool
     */
    public function checkState($state): bool
    {
        return $this->payload['state'] === $state;
    }

    /**
     * Returns process name.
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->payload[$offset] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->payload[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new \LogicException('Process object cannot be altered');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new \LogicException('Process object cannot be altered');
    }
}
