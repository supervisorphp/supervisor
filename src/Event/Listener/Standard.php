<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event\Listener;

use Indigo\Supervisor\Event\Listener;
use Indigo\Supervisor\Event\Handler;
use Indigo\Supervisor\Event\Notification;
use Indigo\Supervisor\Exception\EventHandlingFailed;
use Indigo\Supervisor\Exception\StopListener;

/**
 * Listener for standard IO streams
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Standard implements Listener
{
    /**
     * @var resource
     */
    protected $inputStream = STDIN;

    /**
     * @var resource
     */
    protected $outputStream = STDOUT;

    /**
     * @param resource $inputStream
     * @param resource $outputStream
     */
    public function __construct($inputStream = STDIN, $outputStream = STDOUT)
    {
        $this->assertValidStreamResource($inputStream);
        $this->assertValidStreamResource($outputStream);

        $this->inputStream = $inputStream;
        $this->outputStream = $outputStream;
    }

    /**
     * Returns the input stream
     *
     * @return resource
     */
    public function getInputStream()
    {
        return $this->inputStream;
    }

    /**
     * Returns the output stream
     *
     * @return resource
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * Asserts that a given input is a valid stream resource
     *
     * @param resource $stream
     *
     * @throws \InvalidArgumentException If $stream is not a valid resource
     */
    private function assertValidStreamResource($stream)
    {
        if (!is_resource($stream)) {
            throw new \InvalidArgumentException('Invalid resource for IO stream');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function listen(Handler $handler)
    {
        while (true) {
            $this->write("READY\n");

            if ($notification = $this->getNotification()) {
                try {
                    $handler->handle($notification);
                    $this->write("RESULT 2\nOK");
                } catch (EventHandlingFailed $e) {
                    $this->write("RESULT 4\nFAIL");
                } catch (StopListener $e) {
                    break;
                }
            }
        }
    }

    /**
     * Returns notification from input stream if available
     *
     * @return Notification
     */
    protected function getNotification()
    {
        if ($header = $this->read()) {
            $header = $this->parseData($header);

            $payload = $this->read($header['len']);
            $payload = explode("\n", $payload, 2);
            isset($payload[1]) or $payload[1] = null;

            list($payload, $body) = $payload;

            $payload = $this->parseData($payload);

            return new Notification($header, $payload, $body);
        }
    }

    /**
     * Parses colon devided data
     *
     * @param string $rawData
     *
     * @return array
     */
    protected function parseData($rawData)
    {
        $outputData = [];

        foreach (explode(' ', $rawData) as $data) {
            $data = explode(':', $data);
            $outputData[$data[0]] = $data[1];
        }

        return $outputData;
    }

    /**
     * Reads data from input stream
     *
     * @param integer $length If given read this size of bytes, read a line anyway
     *
     * @return string
     */
    protected function read($length = null)
    {
        if (is_null($length)) {
            return trim(fgets($this->inputStream));
        }

        return fread($this->inputStream, $length);
    }

    /**
     * Writes data to output stream
     *
     * @param string $value
     *
     * @return integer
     */
    protected function write($value)
    {
        return @fwrite($this->outputStream, $value);
    }
}
