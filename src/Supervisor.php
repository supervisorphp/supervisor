<?php

namespace Supervisor;

use fXmlRpc\ClientInterface;
use fXmlRpc\Exception\FaultException;
use Supervisor\Exception\Fault;

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
 * @method bool reloadConfig()
 *
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
final class Supervisor
{
    /**
     * Service states.
     */
    public const SHUTDOWN = -1;
    public const RESTARTING = 0;
    public const RUNNING = 1;
    public const FATAL = 2;

    /**
     * @var ClientInterface
     */
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * Checks if a connection is present.
     *
     * It is done by sending a bump request to the server and catching any thrown exceptions
     *
     * @return bool
     */
    public function isConnected(): bool
    {
        try {
            $this->call('system', 'listMethods');
        } catch (\Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Calls a method.
     *
     * @param string $namespace
     * @param string $method
     * @param array $arguments
     *
     * @return mixed
     */
    public function call(string $namespace, string $method, array $arguments = [])
    {
        try {
            return $this->client->call($namespace . '.' . $method, $arguments);
        } catch (FaultException $faultException) {
            throw Fault::create($faultException);
        }
    }

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
    public function __call(string $method, array $arguments)
    {
        return $this->call('supervisor', $method, $arguments);
    }

    /**
     * Is service running?
     *
     * @return bool
     */
    public function isRunning(): bool
    {
        return $this->checkState(self::RUNNING);
    }

    /**
     * Checks if supervisord is in given state.
     *
     * @param int $checkState
     *
     * @return bool
     */
    public function checkState(int $checkState): bool
    {
        $state = $this->getState();

        return $state['statecode'] === $checkState;
    }

    /**
     * Returns all processes as Process objects.
     *
     * @return Process[]
     */
    public function getAllProcesses(): array
    {
        $processes = $this->getAllProcessInfo();

        foreach ($processes as $key => $processInfo) {
            $processes[$key] = new Process($processInfo);
        }

        return $processes;
    }

    /**
     * Returns a specific Process.
     *
     * @param string $name Process name or 'group:name'
     *
     * @return Process
     */
    public function getProcess(string $name): Process
    {
        $process = $this->getProcessInfo($name);

        return new Process($process);
    }
}
