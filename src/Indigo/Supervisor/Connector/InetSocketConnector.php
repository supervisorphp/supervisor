<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Connector;

/**
 * Connect to Supervisor through internet socket
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class InetSocketConnector extends SocketConnector
{
    /**
     * Hostname
     *
     * @var string
     */
    protected $host;

    /**
     * Post number
     *
     * @var integer
     */
    protected $port;

    public function __construct($host, $port = 9001, $timeout = null, $persistent = false)
    {
        $this->createSocket($host, $port, $timeout, $persistent);

        $this->host = $host;
        $this->port = $port;

        $this->local = gethostbyname($host) == '127.0.0.1';
    }
}
