<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event\Handler;

use Indigo\Supervisor\Event\Handler;
use Indigo\Supervisor\Event\Notification;

/**
 * Accepts a callable
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Callback implements Handler
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Notification $notification)
    {
        call_user_func($this->callback, $notification);
    }
}
