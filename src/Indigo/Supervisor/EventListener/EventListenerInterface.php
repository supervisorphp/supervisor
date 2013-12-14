<?php

namespace Indigo\Supervisor\EventListener;

interface EventListenerInterface
{
	public function listen(array $payload);

	/**
	 * Checks whether listener is listening to the current event
	 *
	 * @param  array   $payload Payload of event holding event name
	 * @return boolean
	 */
	public function isListening(array $payload);

	/**
	 * Returns whether the event propagation should continue or not
	 *
	 * @return boolean
	 */
	public function isPropagationStopped();
}
