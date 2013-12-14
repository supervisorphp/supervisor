<?php

namespace Indigo\Supervisor;

use Indigo\Supervisor\EventListener;

class EventDispatcher
{
	/**
	 * Super constant
	 */
	const EVENT = 0;

	/**
	 * Process state constants
	 */
	const PROCESS_STATE          = 10;
	const PROCESS_STATE_STARTING = 20;
	const PROCESS_STATE_RUNNING  = 30;
	const PROCESS_STATE_BACKOFF  = 40;
	const PROCESS_STATE_STOPPING = 50;
	const PROCESS_STATE_EXITED   = 60;
	const PROCESS_STATE_STOPPED  = 70;
	const PROCESS_STATE_FATAL    = 80;
	const PROCESS_STATE_UNKNOWN  = 90;
	const REMOTE_COMMUNICATION   = 100;

    /**
     * Process log constants
     */
	const PROCESS_LOG        = 110;
	const PROCESS_LOG_STDOUT = 120;
	const PROCESS_LOG_STDERR = 130;

    /**
     * Process communication constants
     */
	const PROCESS_COMMUNICATION        = 140;
	const PROCESS_COMMUNICATION_STDOUT = 150;
	const PROCESS_COMMUNICATION_STDERR = 160;

    /**
     * Supervisor state constants
     */
	const SUPERVISOR_STATE_CHANGE          = 170;
	const SUPERVISOR_STATE_CHANGE_RUNNING  = 180;
	const SUPERVISOR_STATE_CHANGE_STOPPING = 190;

    /**
     * Tick constatns
     */
	const TICK      = 200;
	const TICK_5    = 210;
	const TICK_60   = 220;
	const TICK_3600 = 230;

	/**
	 * Responses sent to supervisor
	 */
	const READY = "READY\n";
	const OK    = "OK";
	const FAIL  = "FAIL";

	protected $listeners = array();

	protected $inputStream;
	protected $outputStream;
	protected $errorStream;

	public function __construct($inputStream = STDIN, $outputStream = STDOUT, $errorStream = STDERR)
	{
		$this->inputStream  = $inputStream;
		$this->outputStream = $outputStream;
		$this->errorStream  = $errortream;
	}

	public function addListener(EventListenerInterface $listener, $prepend = false)
	{
		if ($prepend) {
			array_unshift($this->listeners, $listener);
		} else {
			array_push($this->listeners, $listener);
		}

		return $this;
	}

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

			$this->write(self::READY);
		}
	}

	protected function dispatch($payload)
	{
		foreach ($this->listeners as $listener) {
			$listen = null;

			if ($listener->isListening($payload)) {
				// ITT TARTOK: hogy legyen a visszatérési érték
				$result = $listener->listen($payload);

				if ($listener->isPropagationStopped()) {
					break;
				}
			}
		}
	}

	protected function parseData($rawData)
	{
        $outputData = array();
        foreach (explode(' ', $rawData) as $data) {
        	$data = explode(':', $data);
        	$outputData[$data[0]] = $data[1];
        }

        return $outputData;
	}

	protected function read($length = null)
	{
		if (is_null($length)) {
			return trim(fgets($this->inputStream));
		} else {
			return fread($this->inputStream, $length);
		}
	}

	protected function write($value)
	{
		fwrite($this->inputStream, $value);
	}

	protected function result($result)
	{
		$this->write('RESULT ' . strlen($result) . "\n" . $result);
	}
}
