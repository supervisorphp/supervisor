<?php

namespace Indigo\Supervisor\EventListener;

interface EventListenerInterface
{
	public function listen(array $payload);

	/**
	 * Returns whether the event propagation should continue or not
	 *
	 * @return boolean
	 */
	public function isPropagationStopped();
}
