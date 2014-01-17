<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) IndigoPHP Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Connector;

/**
 * Connect to Supervisor through unix domain socket
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class UnixSocketConnector extends SocketConnector
{
    /**
     * Socket path
     *
     * @var string
     */
    protected $socket;

    public function __construct($socket, $timeout = null, $persistent = false)
    {
        if (substr($socket, 0, 7) !== 'unix://') {
            $socket = 'unix://' . $socket;
        }

        $this->createSocket($socket, -1, $timeout, $persistent);

        $this->socket = $socket;
    }
}
