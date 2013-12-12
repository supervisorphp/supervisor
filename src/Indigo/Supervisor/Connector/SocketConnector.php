<?php

namespace Indigo\Supervisor\Connector;

use Indigo\Supervisor\Exception\InvalidResourceException;
use Indigo\Supervisor\Exception\InvalidResponseException;

/**
 * Connect to Supervisor through unix domain socket
 */
class SocketConnector extends AbstractConnector
{
	/**
	 * Size of read data
	 */
    const CHUNK_SIZE = 8192;

    /**
     * Create SocketConnector instance
     *
     * @param string $socket
     * @param float  $timeout
     */
    public function __construct($socket, $timeout = null)
    {
        $timeout = $timeout ?: ini_get('default_socket_timeout');

        $this->resource = @fsockopen($socket, -1, $errNo, $errStr, $timeout);

        if ( ! is_resource($this->resource)) {
            throw new InvalidResourceException('Cannot open socket: ' . $errStr, $errNo);
        }
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
        return is_resource($this->resource);
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
            throw new InvalidResourceException('Invalid resource');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = array())
    {
        $xml = xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));

        $headers = array_merge($this->headers, array('Content-Length' => strlen($xml)));

        $request = "POST /RPC2 HTTP/1.1\r\n" . http_build_headers($headers) . "\r\n" . $xml;

        fwrite($this->resource, $request);

        $response = '';

        do {
            $response .= fread($this->resource, self::CHUNK_SIZE);

            if ( ! isset($header) and ($headerLength = strpos($response, "\r\n\r\n")) !== false) {
                $header = substr($response, 0, $headerLength);

                $header = http_parse_headers($header);

                if (array_key_exists('Content-Length', $header)) {
                    $contentLength = $header['Content-Length'];
                } else {
                    throw new InvalidResponseException('No Content-Length field found in HTTP header.');
                }
            }

            $socketInfo = $this->getStreamMetadata();

            if ($socketInfo['timed_out']) {
                throw new \RuntimeException("Read timed-out");
            }

            $bodyStart  = $headerLength + 4;
            $bodyLength = strlen($response) - $bodyStart;

        } while ($this->isConnected() and $bodyLength < $contentLength);

        $response = substr($response, $bodyStart);

        return $this->processResponse($response);
    }

    /**
     * Get stream metadata
     * @return array
     */
    protected function getStreamMetadata()
    {
        return stream_get_meta_data($this->resource);
    }
}
