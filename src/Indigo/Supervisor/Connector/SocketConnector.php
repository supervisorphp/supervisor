<?php

namespace Indigo\Supervisor\Connector;

use Indigo\Supervisor\Exception\HttpException;

/**
 * Connect to Supervisor through socket
 */
abstract class SocketConnector extends AbstractConnector
{
    /**
     * Size of read data
     */
    const CHUNK_SIZE = 8192;

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

        $this->timeout = $timeout;
        $this->persistent = $persistent;

        if (!is_resource($resource)) {
            throw new \UnexpectedValueException('Cannot open socket to ' . $hostname . ': ' . $errStr, $errNo);
        }

        return $this->resource = $resource;
    }

    /**
     * Close connection
     */
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

        $timeout_ok = filter_var($timeout, FILTER_VALIDATE_FLOAT);
        if ($timeout_ok === false || $timeout < 0) {
            throw new \InvalidArgumentException("Timeout must be 0 or a positive float (got $timeout)");
        }

        return $timeout_ok;
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
            throw new \InvalidArgumentException('Stream must be a valid resource, ' . gettype($resource) . 'given.');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = array())
    {
        // generate xml request
        $xml = xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));

        // add length to headers
        $headers = array_merge($this->headers, array('Content-Length' => strlen($xml)));

        // build request
        $request = "POST /RPC2 HTTP/1.1\r\n" . http_build_headers($headers) . "\r\n" . $xml;

        if (!$this->write($request)) {
            throw new \RuntimeException('Cannot write to socket');
        }

        $response = '';
        $bodyStart = 0;

        do {
            $response .= $this->read(self::CHUNK_SIZE);

            // check for headers and parse them
            if (!isset($header) and ($headerLength = strpos($response, "\r\n\r\n")) !== false) {
                $header = substr($response, 0, $headerLength);

                // check for status code
                $http = strtok($header, "\r\n");
                $http = explode(' ', $http, 3);

                if (intval($http[1]) !== 200) {
                    throw new HttpException($http[2], $http[1]);
                }

                $header = http_parse_headers($header);

                if (array_key_exists('Content-Length', $header)) {
                    $contentLength = $header['Content-Length'];
                } else {
                    throw new \RuntimeException('No Content-Length field found in HTTP header.');
                }

                $bodyStart  = $headerLength + 4;
            }

            $socketInfo = $this->getStreamMetadata();

            if ($socketInfo['timed_out']) {
                throw new \RuntimeException("Read timed-out");
            }

            $bodyLength = strlen($response) - $bodyStart;

        } while ($this->isConnected() and $bodyLength < $contentLength);

        $response = substr($response, $bodyStart);

        return $this->processResponse($response);
    }

    /**
     * Get stream metadata
     *
     * @return array
     */
    protected function getStreamMetadata()
    {
        return stream_get_meta_data($this->resource);
    }

    /**
     * Write to resource
     *
     * @param mixed $data
     */
    protected function write($data)
    {
        return @fwrite($this->resource, $data);
    }

    /**
     * Read from resource
     *
     * @param  integer $length
     * @return mixed
     */
    protected function read($length)
    {
        return @fread($this->resource, $length);
    }
}
