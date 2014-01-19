<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) IndigoPHP Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\EventListener;

/**
 * EventListener Interface
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
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
     */
    public function listen();

    /**
     * Get input stream
     *
     * @return resource
     */
    public function getInputStream();

    /**
     * Set input stream
     *
     * @param  resource               $stream
     * @return EventListenerInterface
     */
    public function setInputStream($stream);

    /**
     * Get output stream
     *
     * @return resource
     */
    public function getOutputStream();

    /**
     * Set output stream
     *
     * @param  resource               $stream
     * @return EventListenerInterface
     */
    public function setOutputStream($stream);
}
