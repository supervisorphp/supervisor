<?php

namespace Indigo\Supervisor\EventListener;
use Psr\Log\LoggerAwareTrait;

trait EventListenerTrait implements EventListenerInterface
{
	use LoggerAwareTrait;

	/**
	 * Whether to continue propagation or not
	 *
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
}
