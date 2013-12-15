<?php

namespace Indigo\Supervisor\EventListener;

class NullEventListener extends AbstractEventListener
{
	public function doListen(array $payload)
	{
		//noop
		return 0;
	}
}
