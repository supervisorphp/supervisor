<?php

namespace Indigo\Supervisor\EventListener;

interface EventListenerInterface
{
    /**
     * Responses sent to supervisor
     */
    const READY = "READY\n";
    const OK    = "RESULT 2\nOK";
    const FAIL  = "RESULT 4\nFAIL";

    /**
     * Listen to events
     *
     * @param boolean $once Listen should run only once if true
     */
    public function listen($once = false);

    /**
     * Set input stream
     *
     * @param  resource               $stream
     * @return EventListenerInterface
     */
    public function setInputStream($stream);

    /**
     * Set output stream
     *
     * @param  resource               $stream
     * @return EventListenerInterface
     */
    public function setOutputStream($stream);
}
