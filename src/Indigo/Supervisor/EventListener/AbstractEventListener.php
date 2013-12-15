<?php

namespace Indigo\Supervisor\EventListener;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractEventListener implements EventListenerInterface, LoggerAwareInterface
{

	/**
	 * Psr logger
	 *
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * Whether continue propagation or not
	 * @var boolean
	 */
	protected $propagate = true;

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
