<?php

namespace Supervisor;

/**
 * Process object holding data for a single process.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Buster Neece <buster@busterneece.com>
 */
interface ProcessInterface extends \ArrayAccess
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
     * Returns the process info array.
     *
     * @return array
     */
    public function getPayload(): array;

    /**
     * Returns the process name.
     *
     * @return string
     */
    public function getName(): string;

    /**
     * Checks whether the process is running.
     *
     * @return bool
     */
    public function isRunning(): bool;

    /**
     * Checks if process is in the given state.
     *
     * @param int $state
     *
     * @return bool
     */
    public function checkState(int $state): bool;

    /**
     * Returns process name.
     *
     * @return string
     */
    public function __toString(): string;
}
