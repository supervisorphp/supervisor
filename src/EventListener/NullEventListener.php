<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\EventListener;

use Indigo\Supervisor\Event\EventInterface;

/**
 * Null EventListener
 *
 * Used for development purposes
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class NullEventListener extends AbstractEventListener
{
    public function doListen(EventInterface $event)
    {
        // Noop
        return 0;
    }
}
