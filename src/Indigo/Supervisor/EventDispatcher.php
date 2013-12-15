<?php

namespace Indigo\Supervisor;

use Indigo\Supervisor\EventListener\EventListenerInterface;
use Indigo\Supervisor\Exception\InvalidResourceException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class EventDispatcher implements LoggerAwareInterface
{
	/**
	 * Responses sent to supervisor
	 */
	const READY = "READY\n";
	const OK    = "RESULT 2\nOK";
	const FAIL  = "RESULT 4\nFAIL";

	/**
	 * Psr logger
	 *
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * Array of EventListenerInterfaces
	 *
	 * @var array
	 */
	protected $listeners = array();

	/**
	 * Input stream
	 *
	 * @var resource
	 */
	protected $inputStream;

	/**
	 * Output stream
	 *
	 * @var resource
	 */
	protected $outputStream;

	/**
	 * Create new EventDispatcher instance
	 *
	 * @param resource $inputStream
	 * @param resource $outputStream
	 */
	public function __construct($inputStream = STDIN, $outputStream = STDOUT)
	{
		if ( ! is_resource($inputStream)) {
			throw new InvalidResourceException('Input stream is not a valid resource');
		}

		if ( ! is_resource($outputStream)) {
			throw new InvalidResourceException('Input stream is not a valid resource');
		}

		$this->inputStream  = $inputStream;
		$this->outputStream = $outputStream;

		// setting default logger
		$this->logger = new NullLogger();
	}

	/**
	 * Add event listener
	 *
	 * @param  EventListenerInterface $listener
	 * @param  boolean                $prepend
	 * @return EventDispatcher
	 */
	public function addListener(EventListenerInterface $listener, $prepend = false)
	{
		if ($prepend) {
			array_unshift($this->listeners, $listener);
		} else {
			array_push($this->listeners, $listener);
		}

		return $this;
	}

	/**
	 * Listen for events
	 */
	public function listen()
	{
		$this->write(self::READY);

		while (true) {
			if ( ! $headers = $this->read()) {
				continue;
			}

			$headers = $this->parseData($headers);

			$payload = $this->read($headers['len']);

			$payload = explode("\n", $payload, 2);

			$payload[0] = array_merge($headers, $this->parseData($payload[0]));

			$result = $this->dispatch($payload);

			if ($result === 0) {
				$this->write(self::OK);
			} elseif ($result === 1) {
				$this->write(self::FAIL);
			} else {
				return;
			}

			$this->write(self::READY);
		}
	}

	/**
	 * Dispatch event and call listener
	 *
	 * @param  array   $payload Array of header and body
	 * @return integer          0 on success, 1 on failure, any other will stop the dispatcher
	 */
	protected function dispatch($payload)
	{
		// default return code is 0
		$result = 0;

		foreach ($this->listeners as $listener) {
			$result |= $listener->listen($payload);

			// stop propagation, return current result
			if ($listener->isPropagationStopped()) {
				break;
			}
		}

		return (int)$result;
	}

	/**
	 * Parse colon devided data
	 *
	 * @param  string $rawData
	 * @return array
	 */
	protected function parseData($rawData)
	{
        $outputData = array();

        foreach (explode(' ', $rawData) as $data) {
        	$data = explode(':', $data);
        	$outputData[$data[0]] = $data[1];
        }

        return $outputData;
	}

	/**
	 * Read data from input stream
	 *
	 * @param  integer $length If given read this size of bytes, read a line anyway
	 * @return string
	 */
	protected function read($length = null)
	{
		if (is_null($length)) {
			return trim(fgets($this->inputStream));
		} else {
			return fread($this->inputStream, $length);
		}
	}

	/**
	 * Write data to output stream
	 *
	 * @param  string $value
	 */
	protected function write($value)
	{
		fwrite($this->outputStream, $value);
	}

    /**
     * Sets a logger
     *
     * @param LoggerInterface $logger
     */
	public function setLogger(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}
}
