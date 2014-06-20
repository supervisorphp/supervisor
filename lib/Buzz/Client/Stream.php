<?php

namespace Buzz\Client;

use Buzz\Message\MessageInterface;
use Buzz\Message\RequestInterface;
use Buzz\Exception\ClientException;
use RuntimeException;
use Exception;

class Stream extends AbstractStream
{
    const CHUNK_SIZE = 4096;

    /**
     * Stream socket
     *
     * @var resource
     */
    protected $stream;

    /**
     * Creates socket client
     *
     * @param resource $stream
     */
    public function __construct($stream)
    {
        $this->stream = $stream;
    }

    /**
     * Creates a new client
     *
     * @param  string  $remote_socket
     * @param  integer $timeout
     * @param  integer $flags
     *
     * @return Stream
     */
    public static function create($remote_socket, $timeout = 30, $flags = STREAM_CLIENT_CONNECT)
    {
        $errno = 0;
        $errstr = null;

        $level = error_reporting(0);
        $stream = stream_socket_client($remote_socket, $errno, $errstr, $timeout, $flags);
        error_reporting($level);

        if ($stream === false)
        {
            throw new RuntimeException(
                'Cannot create connection.',
                $errno,
                new Exception($errstr, $errno)
            );
        }

        return new static($stream);
    }

    /**
     * Returns the stream
     *
     * @return resource
     */
    public function getStream()
    {
        return $this->stream;
    }

    /**
     * Sets the stream
     *
     * @param resource $stream
     *
     * @return this
     */
    public function setStream($stream)
    {
        $this->stream = $stream;

        return $this;
    }

    /**
     * {@inheritdocs}
     *
     * @see ClientInterface
     */
    public function send(RequestInterface $request, MessageInterface $response)
    {
        if ($this->write($request) === false) {
            throw new ClientException('Cannot write ' . strlen($request) . ' bytes to stream.');
        }

        $this->readResponse($response);

        return $response->getContent();
    }

    public function readResponse(MessageInterface $response)
    {
        $headers = array();
        $hasHeaders = false;
        $body = '';

        while ($this->isEof() === false) {
            if ($hasHeaders === false) {
                $data = $this->readLine();

                if (empty($data))
                {
                    $response->setHeaders($headers);
                    $hasHeaders = true;
                }

                $headers[] = $data;
            } else {
                $data .= $this->read(self::CHUNK_SIZE);
            }
        }

        $response->setContent($this->getContents());
    }

    /**
     * Write to socket
     *
     * @param string $data
     *
     * @return integer Bytes written
     */
    protected function write($data)
    {
        $level = error_reporting(0);
        $bytes = fwrite($this->stream, $data);
        error_reporting($level);

        return $bytes;
    }

    /**
     * Reads from stream
     *
     * @param integer $length
     *
     * @return string
     */
    protected function read($length)
    {
        $level = error_reporting(0);
        $data = fread($this->stream, $length);
        error_reporting($level);

        return $data;
    }

    /**
     * Reads a line from stream
     *
     * @param integer $length
     * @param string  $ending
     *
     * @return string
     */
    protected function readLine($length = self::CHUNK_SIZE, $ending = "\r\n")
    {
        return stream_get_line($this->stream, $length, $ending);
    }

    /**
     * Returns the remaining contents in a string, up to maxlength bytes.
     *
     * @param integer $maxLength
     *
     * @return string
     */
    public function getContents($maxLength = -1)
    {
        return stream_get_contents($this->stream);
    }

    /**
     * Check whether stream pointer is at EOF
     *
     * @return boolean
     *
     * @since 2.0
     */
    public function isEof()
    {
        return feof($this->stream);
    }

    /**
     * Get stream metadata
     *
     * @return array
     */
    protected function getStreamMetadata()
    {
        return stream_get_meta_data($this->stream);
    }

    /**
     * Set timeout on stream
     *
     * @param integer  $seconds
     * @param integer  $microseconds
     *
     * @return boolean
     *
     * @since 2.0
     */
    public function setTimeout($seconds, $microseconds = 0)
    {
        return stream_set_timeout($this->stream, $seconds, $microseconds);
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
