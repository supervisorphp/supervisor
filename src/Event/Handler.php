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

use Indigo\Supervisor\Exception\EventHandlingFailed;
use Indigo\Supervisor\Exception\StopListener;

/**
 * Handles Notifications
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Handler
{
    /**
     * Handles a notification
     *
     * @param Notification $notification
     *
     * @throws EventHandlingFailed If event handling fails
     * @throws StopListener        If listener should be stopped
     */
    public function handle(Notification $notification);
}
