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
 * Listens to events
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Listener
{
    /**
     * Starts listening for events
     */
    public function listen(Handler $handler);
}
