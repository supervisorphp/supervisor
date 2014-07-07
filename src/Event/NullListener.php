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
 * Null EventListener
 *
 * Used for development purposes
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class NullListener extends AbstractListener
{
    /**
     * {@inheritdocs}
     */
    public function doListen(EventInterface $event)
    {
        // Noop
        return 0;
    }
}
