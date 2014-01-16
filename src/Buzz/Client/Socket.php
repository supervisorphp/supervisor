<?php

namespace Buzz\Client;

use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Buzz\Exception\ClientException;

class Socket extends AbstractStream
{
    /**
     * Size of read data
     */
    const CHUNK_SIZE = 8192;

    /**
     * @var resource Socket
     */
    protected $socket;

    /**
     * @param resource $socket
     */
    public function __construct($socket)
    {
        if ($socket) {
            $this->setSocket($socket);
        }
    }

    /**
     * Get socket
     *
     * @return resource
     */
    public function getSocket()
    {
        return $this->socket;
    }

    /**
     * Set socket
     *
     * @param  resource $socket
     * @return Socket
     */
    public function setSocket($socket)
    {
        $this->socket = $socket;

        return $this;
    }

    /**
     * @see ClientInterface
     *
     * @throws ClientException
     */
    public function send(RequestInterface $request, MessageInterface $response)
    {
        $request = $this->prepareRequest($request);
        $this->sendRequest($request);
        $this->receiveResponse($response);

        return $response->getContent();
    }

    protected function prepareRequest(RequestInterface $request)
    {
        $rawRequest = sprintf(
            "%s %s HTTP/%.1f\r\n",
            $request->getMethod(),
            $request->getResource(),
            $request->getProtocolVersion()
        );

        $rawRequest .= implode("\r\n", $request->getHeaders());
        $rawRequest .= "\r\n\r\n";
        $rawRequest .= $request->getContent();

        return $rawRequest;
    }

    protected function sendRequest($request)
    {
        if (!$write = $this->write($request)) {
            throw new ClientException('Cannot write' . strlen($request) . ' bytes to socket');
        }

        return $write;
    }

    protected function receiveResponse(MessageInterface $response)
    {
        $data       = '';
        $contentLen = 0;
        $bodyLen    = -1;

        do {
            $this->checkTimedOut();

            $data .= $this->read(self::CHUNK_SIZE);

            if ($response->getHeaders() === array() and ($hPos = strpos($data, "\r\n\r\n")) !== false) {
                $header = substr($data, 0, $hPos);
                $data = substr($data, $hPos + 4);

                $header = explode("\r\n", $header);

                $response->setHeaders($header);

                if (!$response->isOk()) {
                    throw new ClientException('HTTP Status: ' . $response->getReasonPhrase(), $response->getStatusCode());
                }

                $contentLen = $response->getHeader('Content-Length', '');
            }

            $contentLen > 0 and $bodyLen = strlen($data);

        } while ($bodyLen < $contentLen);

        $this->checkTimedOut();

        $response->setContent($data);
    }

    protected function write($data)
    {
        return @fwrite($this->socket, $data);
    }

    protected function read($length)
    {
        return @fread($this->socket, $length);
    }

    /**
     * Get stream metadata
     *
     * @return array
     */
    protected function getStreamMetadata()
    {
        return stream_get_meta_data($this->socket);
    }

    /**
     * Check whether connection is timed out
     *
     * @return boolean
     */
    protected function isTimedOut()
    {
        $info = $this->getStreamMetadata();

        return $info['timed_out'];
    }

    /**
     * Handle connection timeout
     *
     * @throws ClientException Connection timed out
     */
    protected function checkTimedOut()
    {
        if ($this->isTimedOut()) {
            throw new ClientException("Connection timed-out");
        }
    }
}
