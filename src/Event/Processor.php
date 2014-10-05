<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event;

/**
 * Processor interface
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Processor
{
    /**
     * Responses sent to supervisor
     */
    const READY = "READY\n";
    const OK    = "RESULT 2\nOK";
    const FAIL  = "RESULT 4\nFAIL";

    /**
     * Quit result from event
     */
    const QUIT = false;

    /**
     * Runs processor, starts listening for events
     */
    public function run();
}
