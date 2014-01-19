<?php

namespace Indigo\Supervisor\EventListener;

use Indigo\Supervisor\Event\Event;

class NullEventListener extends AbstractEventListener
{
    public function doListen(EventInterface $event)
    {
        // Noop
        return 0;
    }
}
