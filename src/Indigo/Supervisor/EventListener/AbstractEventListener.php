<?php

namespace Indigo\Supervisor\EventListener;
use Psr\Log;

abstract class AbstractEventListener implements EventListenerInterface, LoggerAwareInterface
{
	/**
	 * Process state constants
	 */
	const PROCESS_STATE_STARTING = 1;
	const PROCESS_STATE_RUNNING  = 2;
	const PROCESS_STATE_BACKOFF  = 4;
	const PROCESS_STATE_STOPPING = 8;
	const PROCESS_STATE_EXITED   = 16;
	const PROCESS_STATE_STOPPED  = 32;
	const PROCESS_STATE_FATAL    = 64;
	const PROCESS_STATE_UNKNOWN  = 128;
	const REMOTE_COMMUNICATION   = 256;
	const PROCESS_STATE          = 511;

    /**
     * Process log constants
     */
	const PROCESS_LOG_STDOUT = 512;
	const PROCESS_LOG_STDERR = 1024;
	const PROCESS_LOG        = 1536;

    /**
     * Process communication constants
     */
	const PROCESS_COMMUNICATION_STDOUT = 2048;
	const PROCESS_COMMUNICATION_STDERR = 4096;
	const PROCESS_COMMUNICATION        = 6144;

    /**
     * Supervisor state constants
     */
	const SUPERVISOR_STATE_CHANGE_RUNNING  = 8192;
	const SUPERVISOR_STATE_CHANGE_STOPPING = 16384;
	const SUPERVISOR_STATE_CHANGE          = 34576;

    /**
     * Tick constatns
     */
	const TICK_5    = 32768;
	const TICK_60   = 65536;
	const TICK_3600 = 131072;
	const TICK      = 229376;

	/**
	 * Super constant
	 */
	const EVENT = 262143;

	/**
	 * Psr logger
	 *
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * The current subscribed events
	 * @var integer
	 */
	protected $events;

	/**
	 * Whether continue propagation or not
	 * @var boolean
	 */
	protected $propagate = true;

	/**
	 * {@inheritdoc}
	 */
	public function isListening(array $payload)
	{
		return $this->isListeningTo($payload[0]['eventname']);
	}

	/**
	 * Checks whether listener is listening to specific event(s)
	 *
	 * @param  mixed   $event Event integer value or name of event
	 * @return boolean
	 */
	public function isListeningTo($event)
	{
		$event = $this->getEvent($event);

		return ($this->events & $event) == true;
	}

	/**
	 * Subscribe to an event (whether you subscribed for it before or not)
	 *
	 * @param  mixed                  $event Event integer value or name of event
	 * @return EventListenerInterface
	 */
	public function subscribeEvent($event)
	{
		$event = $this->getEvent($event);

		$this->events |= $event;

		return $this;
	}

	/**
	 * Unsubscribe from an event (whether you subscribed for it before or not)
	 *
	 * @param  mixed                  $event Event integer value or name of event
	 * @return EventListenerInterface
	 */
	public function unsubscribeEvent($event)
	{
		$event = $this->getEvent($event);

		$this->events = $this->events & ~ $event;

		return $this;
	}

	/**
	 * Return the event(s) integer value
	 *
	 * @param  mixed $event Event integer value or name of event
	 * @return integer      Integer value of event(s)
	 */
	protected function getEvent($event)
	{
		if (is_numeric($event)) {
			$event = intval($event);
		} else {
			$event = 'self::' . $event;
			$event = defined($event) ? constant($event) : 0;
		}

		return $event;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isPropagationStopped()
	{
		return ! $this->propagate;
	}

	/**
	 * Prevents further listeners from being fired
	 *
	 * @return EventListenerInterface
	 */
	public function stopPropagation()
	{
		$this->propagate = false;

		return $this;
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
