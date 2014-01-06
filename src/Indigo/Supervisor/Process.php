<?php

namespace Indigo\Supervisor;

use Indigo\Supervisor\Connector\ConnectorInterface;
use Symfony\Component\Process\Process as SymfonyProcess;
use Indigo\Supervisor\Exception\ResponseException;

class Process implements \ArrayAccess, \Iterator
{
    /**
     * Process states
     */
    const STOPPED  = 0;
    const STARTING = 10;
    const RUNNING  = 20;
    const BACKOFF  = 30;
    const STOPPING = 40;
    const EXITED   = 100;
    const FATAL    = 200;
    const UNKNOWN  = 1000;

    /**
     * Connector object
     *
     * @var ConnectorInterface
     */
    protected $connector;

    /**
     * Process info
     *
     * @var array
     */
    protected $payload = array();

    /**
     * Create new Process instance
     *
     * @param array              $payload   Process info
     * @param ConnectorInterface $connector
     */
    public function __construct(array $payload, ConnectorInterface $connector)
    {
        $this->payload = $payload;
        $this->connector = $connector;
    }

    /**
     * Return process info array
     *
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Get name of process
     *
     * @return string
     */
    public function getName()
    {
        return $this['name'];
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
     * Is the process currently running?
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
     * @param  int     $state
     * @return boolean
     */
    public function isState($state = self::RUNNING)
    {
        return $this->payload['state'] == $state;
    }

    /**
     * Get memory usage
     *
     * @return integer Used memory in bytes
     */
    public function getMemUsage()
    {
        $mem = 0;

        if ($this->isRunning() and ! empty($this['pid'])) {
            $process = new SymfonyProcess('ps -orss= -p ' . $this['pid']);
            $process->run();

            if ($process->isSuccessful()) {
                $mem = intval($process->getOutput()) * 1024;
            }
        }

        return $mem;
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
        $arguments = array_merge(array($this->payload['name']), $arguments);

        return $this->connector->call($namespace, $method, $arguments);
    }

    /**
     * Start the process
     *
     * @param  boolean $wait Wait for process to be fully started
     * @return boolean Always true unless error
     */
    public function start($wait = true)
    {
        return $this->call('supervisor', 'startProcess', array($wait));
    }

    /**
     * Stop the process
     *
     * @param  boolean $wait Wait for process to be fully stopped
     * @return boolean Always true unless error
     */
    public function stop($wait = true)
    {
        return $this->call('supervisor', 'stopProcess', array($wait));
    }

    /**
     * Restart the process
     *
     * @param  boolean $wait Wait for process to be fully stopped and started
     * @return boolean Always true unless error
     */
    public function restart($wait = true)
    {
        try {
            $this->stop($wait);
            $this->start($wait);
        } catch (ResponseException $e) {
            return false;
        }

        return true;
    }

    /**
     * Send a string of chars to the stdin of the process name.
     * If non-7-bit data is sent (unicode), it is encoded to utf-8 before being sent to the process’ stdin.
     * If chars is not a string or is not unicode, raise INCORRECT_PARAMETERS.
     * If the process is not running, raise NOT_RUNNING.
     * If the process’ stdin cannot accept input (e.g. it was closed by the child process), raise NO_FILE.
     *
     * @param  string  $data The character data to send to the process
     * @return boolean Always return True unless error
     */
    public function sendStdin($data)
    {
        return $this->call('supervisor', 'sendProcessStdin', array($data));
    }

    /**
     * Read length bytes from stdout log starting at offset
     *
     * @param  int    $offset Offset to start reading from
     * @param  int    $length Number of bytes to read from the log
     * @return string Result Bytes of log
     */
    public function readStdoutLog($offset, $length)
    {
        return $this->call('supervisor', 'readProcessStdoutLog', array($offset, $length));
    }

    /**
     * Read length bytes from stderr log starting at offset
     *
     * @param  int    $offset Offset to start reading from
     * @param  int    $length Number of bytes to read from the log
     * @return string Result Bytes of log
     */
    public function readStderrLog($offset, $length)
    {
        return $this->call('supervisor', 'readProcessStderrLog', array($offset, $length));
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
     * @param  int   $offset Offset to start reading from
     * @param  int   $length Maximum number of bytes to return
     * @return array [string bytes, int offset, bool overflow]
     */
    public function tailStdoutLog($offset, $length)
    {
        return $this->call('supervisor', 'tailProcessStdoutLog', array($offset, $length));
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
     * @param  int   $offset Offset to start reading from
     * @param  int   $length Maximum number of bytes to return
     * @return array [string bytes, int offset, bool overflow]
     */
    public function tailStderrLog($offset, $length)
    {
        return $this->call('supervisor', 'tailProcessStderrLog', array($offset, $length));
    }

    /**
     * Clear the stdout and stderr logs for the process and reopen them.
     *
     * @return boolean Always return true unless error
     */
    public function clearLogs()
    {
        return $this->call('supervisor', 'clearProcessLogs');
    }

    public function __tostring()
    {
        return $this['name'];
    }

    /***************************************************************************
     * Implementation of ArrayAccess
     **************************************************************************/

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->payload[] = $value;
        } else {
            $this->payload[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->payload[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->payload[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->payload[$offset]) ? $this->payload[$offset] : null;
    }

    /***************************************************************************
     * Implementation of Iterable
     **************************************************************************/

    public function rewind()
    {
        reset($this->payload);
    }

    public function current()
    {
        return current($this->payload);
    }

    public function key()
    {
        return key($this->payload);
    }

    public function next()
    {
        return next($this->payload);
    }

    public function valid()
    {
        return key($this->payload) !== null;
    }
}
