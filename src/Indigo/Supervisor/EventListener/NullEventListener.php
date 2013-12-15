<?php

namespace Indigo\Indigo\Supervisor\EventListener;

class NullEventListener extends AbstractEventListener
{
	public function listen(array $payload)
	{
		//noop
		return true;
	}
}
