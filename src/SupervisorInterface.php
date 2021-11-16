<?php

namespace Supervisor;

/**
 * Supervisor API.
 *
 * @method string  getAPIVersion()
 * @method string  getSupervisorVersion()
 * @method string  getIdentification()
 * @method array   getState()
 * @method int getPID()
 * @method string  readLog(integer $offset, integer $limit)
 * @method bool clearLog()
 * @method bool shutdown()
 * @method bool restart()
 * @method array   getProcessInfo(string $processName)
 * @method array   getAllProcessInfo()
 * @method bool startProcess(string $name, boolean $wait = true)
 * @method bool startAllProcesses(boolean $wait = true)
 * @method bool startProcessGroup(string $name, boolean $wait = true)
 * @method bool stopProcess(string $name, boolean $wait = true)
 * @method bool stopAllProcesses(boolean $wait = true)
 * @method bool stopProcessGroup(string $name, boolean $wait = true)
 * @method bool sendProcessStdin(string $name, string $chars)
 * @method bool addProcessGroup(string $name)
 * @method bool removeProcessGroup(string $name)
 * @method string  readProcessStdoutLog(string $name, integer $offset, integer $limit)
 * @method string  readProcessStderrLog(string $name, integer $offset, integer $limit)
 * @method string  tailProcessStdoutLog(string $name, integer $offset, integer $limit)
 * @method string  tailProcessStderrLog(string $name, integer $offset, integer $limit)
 * @method bool clearProcessLogs(string $name)
 * @method bool clearAllProcessLogs()
 * @method array reloadConfig()
 * @method bool signalProcess(string $name, string $signal)
 * @method bool signalProcessGroup(string $name, string $signal)
 * @method bool signalAllProcesses(string $signal)
 *
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 * @author Buster Neece <buster@busterneece.com>
 */
interface SupervisorInterface
{
    /**
     * Service states.
     */
    public const SHUTDOWN = -1;
    public const RESTARTING = 0;
    public const RUNNING = 1;
    public const FATAL = 2;

    /**
     * Calls a method.
     *
     * @param string $namespace
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function call(string $namespace, string $method, array $arguments = []);

    /**
     * Magic __call method.
     *
     * Handles all calls to supervisor namespace
     *
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function __call(string $method, array $arguments);

    /**
     * Checks if a connection is present.
     *
     * It is done by sending a bump request to the server and catching any thrown exceptions
     *
     * @return bool
     */
    public function isConnected(): bool;

    /**
     * Is service running?
     *
     * @return bool
     */
    public function isRunning(): bool;

    /**
     * Checks if supervisord is in given state.
     *
     * @param int $checkState
     *
     * @return bool
     */
    public function checkState(int $checkState): bool;

    /**
     * Returns all processes as Process objects.
     *
     * @return ProcessInterface[]
     */
    public function getAllProcesses(): array;

    /**
     * Returns a specific Process.
     *
     * @param string $name Process name or 'group:name'
     *
     * @return ProcessInterface
     */
    public function getProcess(string $name): ProcessInterface;
}
