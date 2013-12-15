<?php

namespace Indigo\Supervisor\EventListener;

interface EventListenerInterface
{
	/**
	 * Listen to events
	 *
	 * @param  array   $payload Array of header and body
	 * @return boolean          True on success, false on failure
	 */
	public function listen(array $payload);

	/**
	 * Returns whether the event propagation should continue or not
	 *
	 * @return boolean
	 */
	public function isPropagationStopped();
}
