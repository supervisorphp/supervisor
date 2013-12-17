<?php

namespace Indigo\Supervisor\Connector;

/**
 * Connect to Supervisor through internet socket
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

    public function __construct($host, $port = 9001, $timeout = null, $persistent = false) {
        $this->createSocket($host, $port, $timeout, $persistent);

        $this->host = $host;
        $this->port = $port;
    }
}
