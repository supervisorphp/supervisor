<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor;

/**
 * Manage supervisor instance
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Supervisor
{
    /**
     * Service states
     */
    const SHUTDOWN   = -1;
    const RESTARTING = 0;
    const RUNNING    = 1;
    const FATAL      = 2;

    /**
     * @var Connector
     */
    protected $connector;

    /**
     * @param Connector $connector
     */
    public function __construct(Connector $connector)
    {
        $this->connector = $connector;
    }

    /**
     * Checks whether connecting to a local Supervisor instance
     *
     * @return boolean
     */
    public function isLocal()
    {
        return $this->connector->isLocal();
    }

    /**
     * Calls a method
     *
     * @param string $namespace Namespace of method
     * @param string $method    Method name
     * @param []     $arguments Argument list
     *
     * @return mixed
     */
    public function call($namespace, $method, array $arguments = [])
    {
        return $this->connector->call($namespace, $method, $arguments);
    }

    /**
     * Magic __call method
     *
     * Handles all calls to supervisor namespace
     */
    public function __call($method, $arguments)
    {
        $process = reset($arguments);

        if ($process instanceof Process) {
            array_shift($arguments);

            return $process->call('supervisor', $method, $arguments);
        }

        return $this->call('supervisor', $method, $arguments);
    }

    /**
     * Status and control
     */

    /**
     * Is service running?
     *
     * @return boolean
     */
    public function isRunning()
    {
        return $this->isState();
    }

    /**
     * Checks if supervisord is in given state
     *
     * @param integer $isState
     *
     * @return boolean
     */
    public function isState($isState = self::RUNNING)
    {
        $state = $this->getState();

        return $state['statecode'] == $isState;
    }

    /**
     * Process control
     */

    /**
     * Returns all processes as Process objects
     *
     * @return array Array of Process objects
     *
     * @codeCoverageIgnore
     */
    public function getAllProcesses()
    {
        $processes = $this->getAllProcessInfo();

        foreach ($processes as $key => $processInfo) {
            $processes[$key] = new Process($processInfo, $this->connector);
        }

        return $processes;
    }

    /**
     * Returns a specific Process
     *
     * @param string $name Process name or 'group:name'
     *
     * @return Process
     */
    public function getProcess($name)
    {
        return new Process($name, $this->connector);
    }
}
