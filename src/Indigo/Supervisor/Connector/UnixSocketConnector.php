<?php

namespace Indigo\Supervisor\Connector;

/**
 * Connect to Supervisor through unix domain socket
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