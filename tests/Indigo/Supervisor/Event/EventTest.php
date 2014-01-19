<?php

namespace Indigo\Supervisor\Event;

class EventTest extends AbstractEventTest
{
    public function setUp()
    {
        $this->event = new Event($this->header, $this->payload, $this->body);
    }
}
