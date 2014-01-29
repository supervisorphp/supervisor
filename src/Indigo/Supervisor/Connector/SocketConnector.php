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

use Buzz\Message\Request;
use Buzz\Client\Socket as Client;

/**
 * Connect to Supervisor through socket
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class SocketConnector extends AbstractConnector
{
    /**
     * Timeout
     *
     * @var float
     */
    protected $timeout;

    /**
     * Persistent connection
     *
     * @var boolean
     */
    protected $persistent = false;

    /**
     * Create socket connection
     *
     * @param string  $hostname   Internet or unix domain
     * @param integer $port       Port number
     * @param integer $timeout    Connection timeout in seconds
     * @param boolean $persistent Use persistent connection
     */
    protected function createSocket($hostname, $port = -1, $timeout = null, $persistent = false)
    {
        $timeout = $this->validateTimeout($timeout);

        if ($persistent) {
            $resource = @pfsockopen($hostname, $port, $errNo, $errStr, $timeout);
        } else {
            $resource = @fsockopen($hostname, $port, $errNo, $errStr, $timeout);
        }

        if (!is_resource($resource)) {
            throw new \RuntimeException('Cannot open socket to ' . $hostname . ': ' . $errStr, $errNo);
        }

        $this->timeout = $timeout;
        $this->persistent = $persistent;

        return $this->resource = $resource;
    }

    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritdoc}
     */
    public function isConnected()
    {
        return is_resource($this->resource) and ! feof($this->resource);
    }

    /**
     * Is it a persistent connection?
     *
     * @return boolean
     */
    public function isPersistent()
    {
        return $this->persistent;
    }

    /**
     * Set timeout if there is a connection
     *
     * @param mixed $timeout
     */
    public function setTimeout($timeout = null)
    {
        $timeout = $this->validateTimeout($timeout);
        $this->timeout = $timeout;

        if ($this->isConnected()) {
            return stream_set_timeout($this->resource, $timeout);
        }

        return false;
    }

    /**
     * Validate timeout
     *
     * @param  mixed $timeout Timeout value
     * @return float Validated float timeout
     */
    protected function validateTimeout($timeout = null)
    {
        is_null($timeout) and $timeout = ini_get("default_socket_timeout");

        $timeoutOk = filter_var($timeout, FILTER_VALIDATE_FLOAT);
        if ($timeoutOk === false || $timeout < 0) {
            throw new \InvalidArgumentException("Timeout must be 0 or a positive float (got $timeout)");
        }

        return $timeoutOk;
    }

    /**
     * Close socket
     */
    public function close()
    {
        if ($this->isConnected()) {
            @fclose($this->resource);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setResource($resource)
    {
        if (is_resource($resource)) {
            return parent::setResource($resource);
        } else {
            throw new \InvalidArgumentException('Stream must be a valid resource, ' . gettype($resource) . ' given.');
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareRequest($namespace, $method, array $arguments)
    {
        // generate xml request
        $xml = xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));

        // add length to headers
        $headers = array_merge($this->headers, array('Content-Length' => strlen($xml)));

        $request = new Request('POST', '/RPC2');
        $request->setProtocolVersion(1.1);
        $request->setHeaders($headers);
        $request->setContent($xml);

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareClient()
    {
        return new Client($this->resource);
    }
}
