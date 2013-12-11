<?php

namespace Indigo\Supervisor;

use Indigo\Supervisor\Connector\ConnectorInterface;

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
	 * Connector object
	 *
	 * @var ConnectorInterface
	 */
	protected $connector;

	/**
	 * Create new Supervisor instance
	 *
	 * @param ConnectorInterface $connector
	 */
	public function __construct(ConnectorInterface $connector)
	{
		$this->connector = $connector;
	}

	/**
	 * Return connector object
	 *
	 * @return ConnectorInterface
	 */
	public function getConnector()
	{
		return $this->connector;
	}

	/**
	 * Set connector
	 *
	 * @param ConnectorInterface $connector
	 */
	public function setConnector(ConnectorInterface $connector)
	{
		$this->connector = $connector;
	}

	/**
	 * Call a method
	 *
	 * @param  string $namespace Namespace of method
	 * @param  string $method    Method name
	 * @param  array  $arguments Argument list
	 * @return mixed
	 */
	public function call($namespace, $method, array $arguments = array())
	{
		return $this->connector->call($namespace, $method, $arguments);
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
	 * Check against state
	 *
	 * @param  int     $isState
	 * @return boolean
	 */
	public function isState($isState = self::RUNNING)
	{
		$state = $this->getState();
		return $state['statecode'] == $isState;
	}

	/**
	 * Return the version of the RPC API used by supervisord
	 *
	 * @return string
	 */
	public function getAPIVersion()
	{
		return $this->call('supervisor', 'getAPIVersion');
	}

	/**
	 * Return the version of the supervisor package in use by supervisord
	 *
	 * @return string
	 */
	public function getSupervisorVersion()
	{
		return $this->call('supervisor', 'getSupervisorVersion');
	}

	/**
	 * Return the PID of supervisord
	 *
	 * @return int
	 */
	public function getPID()
	{
		return $this->call('supervisor', 'getPID');
	}

	/**
	 * Return current state of supervisord
	 *
	 * @return array Array of string statecode, int statename
	 */
	public function getState()
	{
		return $this->call('supervisor', 'getState');
	}

	/**
	 * Read length bytes from the main log starting at offset
	 *
	 * @param  int    $offset Offset to start reading from
	 * @param  int    $length Number of bytes to read from the log
	 * @return string         Result Bytes of log
	 */
    public function readLog($offset, $length)
    {
        return $this->call('supervisor', 'readLog', array($offset, $length));
    }

    /**
     * Clear the main log
     *
     * @return boolean Always true unless error
     */
    public function clearLog()
    {
        return $this->call('supervisor', 'clearLog');
    }

    /**
     * Shut down the supervisor process
     *
     * @return boolean Always true unless error
     */
    public function shutdown()
    {
        return $this->call('supervisor', 'shutdown');
    }

    /**
     * Restart the supervisor process
     *
     * @return boolean Always true unless error
     */
    public function restart()
    {
        return $this->call('supervisor', 'restart');
    }

    /**
     * Process control
     */

    /**
     * Get all processes as Process objects
     *
     * @return array Array of Process objects
     */
	public function getAllProcess()
	{
		$processes = $this->getAllProcessInfo();

		foreach ($processes as $key => $processInfo) {
			$processes[$key] = new Process($processInfo, $this->connector);
		}

		return $processes;
	}

	/**
	 * Get info about all processes
	 *
	 * @return array
	 */
	public function getAllProcessInfo()
	{
		return $this->call('supervisor', 'getAllProcessInfo');
	}

	/**
	 * Get a specific Process
	 * @param  string $name Process name or 'group:name'
	 * @return Process
	 */
	public function getProcess($name)
	{
		return new Process($this->getProcessInfo($name), $this->connector);
	}

	/**
	 * Get info about a process named name
	 *
	 * @param  string $name Process name or 'group:name'
	 * @return array
	 */
	public function getProcessInfo($name)
	{
		return $this->call('supervisor', 'getProcessInfo', array($name));
	}

	/**
	 * Start all processes listed in the configuration file
	 *
	 * @param  boolean $wait Wait for each process to be fully started
	 * @return array         An array of process status info arrays
	 */
	public function startAllProcesses($wait = true)
	{
		return $this->call('supervisor', 'startAllProcesses', array($wait));
	}

	/**
	 * Start a process
	 *
	 * @param  string|Process  $process Process name or 'group:name' or Process object
	 * @param  boolean         $wait    Wait for process to be fully started
	 * @return boolean                  Always true unless error
	 */
	public function startProcess($process, $wait = true)
	{
		if ($process instanceof Process) {
			return $process->start($wait);
		}

		return $this->call('supervisor', 'startProcess', array($process, $wait));
	}

	/**
	 * Start all processes in the group named ‘name’
	 *
	 * @param  string  $groupName Group name
	 * @param  boolean $wait      Wait for each process to be fully started
	 * @return array              An array of process status info arrays
	 */
	public function startProcessGroup($groupName, $wait = true)
	{
		return $this->call('supervisor', 'startProcessGroup', array($groupName, $wait));
	}

	/**
	 * Stop all processes listed in the configuration file
	 *
	 * @param  boolean $wait Wait for each process to be fully stopped
	 * @return boolean       An array of process status info arrays
	 */
	public function stopAllProcesses($wait = true)
	{
		return $this->call('supervisor', 'stopAllProcesses', array($wait));
	}

	/**
	 * Stop a process
	 *
	 * @param  string|Process  $process Process name or 'group:name' or Process object
	 * @param  boolean         $wait    Wait for process to be fully stopped
	 * @return boolean                  Always true unless error
	 */
	public function stopProcess($process, $wait = true)
	{
		if ($process instanceof Process) {
			return $process->stop($wait);
		}

		return $this->call('supervisor', 'stopProcess', array($process, $wait));
	}

	/**
	 * Stop all processes in the group named ‘name’
	 *
	 * @param  string  $groupName Group name
	 * @param  boolean $wait      Wait for each process to be fully stopped
	 * @return array              An array of process status info arrays
	 */
	public function stopProcessGroup($groupName, $wait = true)
	{
		return $this->call('supervisor', 'stopProcessGroup', array($groupName, $wait));
	}

	/**
	 * Send a string of chars to the stdin of the process name.
	 * If non-7-bit data is sent (unicode), it is encoded to utf-8 before being sent to the process’ stdin.
	 * If chars is not a string or is not unicode, raise INCORRECT_PARAMETERS.
	 * If the process is not running, raise NOT_RUNNING.
	 * If the process’ stdin cannot accept input (e.g. it was closed by the child process), raise NO_FILE.
	 *
	 * @param  string|Process $process Process name or 'group:name' or Process object
	 * @param  string         $data    The character data to send to the process
	 * @return boolean                 Always return True unless error
	 */
	public function sendProcessStdin($process, $data)
	{
		if ($process instanceof Process) {
			return $process->sendStdin($data);
		}

		return $this->call('supervisor', 'sendProcessStdin', array($process, $data));
	}

	/**
	 * Send an event that will be received by event listener subprocesses subscribing to the RemoteCommunicationEvent.
	 *
	 * @param  string  $type String for the “type” key in the event header
	 * @param  string  $data Data for the event body
	 * @return boolean       Always return True unless error
	 */
	public function sendRemoteCommEvent($type, $data)
	{
		return $this->call('supervisor', 'sendRemoteCommEvent', array($type, $data));
	}

	/**
	 * Update the config for a running process from config file
	 *
	 * @param  string $name Name of process group to add
	 * @return boolean
	 */
	public function addProcessGroup($name)
	{
		return $this->call('supervisor', 'addProcessGroup', array($name));
	}

	/**
	 * Remove a stopped process from the active configuration
	 *
	 * @param  string $name Name of process group to remove
	 * @return boolean
	 */
	public function removeProcessGroup($name)
	{
		return $this->call('supervisor', 'removeProcessGroup', array($name));
	}

	/**
	 * Process logging
	 */

	/**
	 * Read length bytes from name’s stdout log starting at offset
	 *
	 * @param  string|Process $process Process name or 'group:name' or Process object
	 * @param  int            $offset  Offset to start reading from
	 * @param  int            $length  Number of bytes to read from the log
	 * @return string                  Result Bytes of log
	 */
    public function readProcessStdoutLog($process, $offset, $length)
    {
		if ($process instanceof Process) {
			return $process->readStdoutLog($offset, $length);
		}

        return $this->call('supervisor', 'readProcessStdoutLog', array($process, $offset, $length));
    }

	/**
	 * Read length bytes from name’s stderr log starting at offset
	 *
	 * @param  string|Process $process Process name or 'group:name' or Process object
	 * @param  int            $offset  Offset to start reading from
	 * @param  int            $length  Number of bytes to read from the log
	 * @return string                  Result Bytes of log
	 */
    public function readProcessStderrLog($process, $offset, $length)
    {
		if ($process instanceof Process) {
			return $process->readStderrLog($offset, $length);
		}

        return $this->call('supervisor', 'readProcessStderrLog', array($process, $offset, $length));
    }

	/**
	 * Provides a more efficient way to tail the (stdout) log than readProcessStdoutLog().
	 * Use readProcessStdoutLog() to read chunks and tailProcessStdoutLog() to tail.
	 *
	 * Requests (length) bytes from the (name)’s log, starting at (offset).
	 * If the total log size is greater than (offset + length),
	 * the overflow flag is set and the (offset) is automatically increased to position the buffer at the end of the log.
	 * If less than (length) bytes are available, the maximum number of available bytes will be returned.
	 * (offset) returned is always the last offset in the log +1.
	 *
	 * @param  string|Process $process Process name or 'group:name' or Process object
	 * @param  int            $offset  Offset to start reading from
	 * @param  int            $length  Maximum number of bytes to return
	 * @return array                   [string bytes, int offset, bool overflow]
	 */
    public function tailProcessStdoutLog($process, $offset, $length)
    {
		if ($process instanceof Process) {
			return $process->tailStdoutLog($offset, $length);
		}

        return $this->call('supervisor', 'tailProcessStdoutLog', array($process, $offset, $length));
    }

	/**
	 * Provides a more efficient way to tail the (stderr) log than readProcessStderrLog().
	 * Use readProcessStderrLog() to read chunks and tailProcessStderrLog() to tail.
	 *
	 * Requests (length) bytes from the (name)’s log, starting at (offset).
	 * If the total log size is greater than (offset + length),
	 * the overflow flag is set and the (offset) is automatically increased to position the buffer at the end of the log.
	 * If less than (length) bytes are available, the maximum number of available bytes will be returned.
	 * (offset) returned is always the last offset in the log +1.
	 *
	 * @param  string|Process $process Process name or 'group:name' or Process object
	 * @param  int            $offset  Offset to start reading from
	 * @param  int            $length  Maximum number of bytes to return
	 * @return array                   [string bytes, int offset, bool overflow]
	 */
    public function tailProcessStderrLog($process, $offset, $length)
    {
		if ($process instanceof Process) {
			return $process->tailStderrLog($offset, $length);
		}

        return $this->call('supervisor', 'tailProcessStderrLog', array($process, $offset, $length));
    }

    /**
     * Clear all process log files
     *
     * @return boolean Always return true
     */
	public function clearAllProcessLogs()
	{
		return $this->call('supervisor', 'clearAllProcessLogs');
	}

	/**
	 * Clear the stdout and stderr logs for the named process and reopen them.
	 *
	 * @param  string|Process $process Process name or 'group:name' or Process object
	 * @return boolean                 Always return true unless error
	 */
	public function clearProcessLogs($process)
	{
		if ($process instanceof Process) {
			return $process->clearLogs();
		}

		return $this->call('supervisor', 'clearProcessLogs', array($process));
	}
}
