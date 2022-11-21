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
     * Returns the process info array.
     */
    public function getPayload(): array;

    /**
     * Returns the process name.
     */
    public function getName(): string;

    /**
     * Checks whether the process is running.
     */
    public function isRunning(): bool;

    /**
     * Checks whether the process is running.
     */
    public function getState(): ProcessStates;

    /**
     * Checks if process is in the given state.
     */
    public function checkState(int|ProcessStates $state): bool;

    /**
     * Returns process name.
     */
    public function __toString(): string;
}
