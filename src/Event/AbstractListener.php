<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use InvalidArgumentException;

/**
 * Abstract EventListener
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractListener implements ListenerInterface, LoggerAwareInterface
{
    /**
     * Psr logger
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Input stream
     *
     * @var resource
     */
    protected $inputStream = STDIN;

    /**
     * Output stream
     *
     * @var resource
     */
    protected $outputStream = STDOUT;

    /**
     * {@inheritdocs}
     */
    public function getInputStream()
    {
        return $this->inputStream;
    }

    /**
     * {@inheritdocs}
     */
    public function setInputStream($stream)
    {
        if (is_resource($stream)) {
            $this->inputStream = $stream;
        } else {
            throw new InvalidArgumentException('Invalid resource for input stream');
        }

        return $this;
    }

    /**
     * {@inheritdocs}
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * {@inheritdocs}
     */
    public function setOutputStream($stream)
    {
        if (is_resource($stream)) {
            $this->outputStream = $stream;
        } else {
            throw new InvalidArgumentException('Invalid resource for output stream');
        }

        return $this;
    }

    /**
     * {@inheritdocs}
     *
     * @codeCoverageIgnore
     */
    public function listen()
    {
        while (true) {
            $this->statusReady();

            if ($event = $this->getEvent()) {
                $result = $this->doListen($event);

                if ($this->processResult($result) === false) {
                    return;
                }
            }
        }
    }

    /**
     * Returns event from input stream if available
     *
     * @return Event Event object
     */
    protected function getEvent()
    {
        if ($event = $this->read()) {
            $header = $this->parseData($event);

            $payload = $this->read($header['len']);
            $payload = explode("\n", $payload, 2);
            isset($payload[1]) or $payload[1] = null;

            list($payload, $body) = $payload;

            $event = $this->resolveEvent(
                $header,
                $this->parseData($payload),
                $body
            );
        }

        return $event;
    }

    /**
     * Resolves an Event
     *
     * Overrideable method to be able to resolve custom events
     *
     * @param array  $header
     * @param array  $payload
     * @param string $body
     *
     * @return EventInterface
     */
    protected function resolveEvent(array $header, array $payload, $body = null)
    {
        return new Event($header, $payload, $body);
    }

    /**
     * Processes result
     *
     * @param integer $result Result code
     *
     * @return boolean Listener should exit or not
     */
    protected function processResult($result)
    {
        switch ($result) {
            case 0:
                $this->write(self::OK);
                break;
            case 1:
                $this->write(self::FAIL);
                break;
            default:
                return false;
                break;
        }

        return true;
    }

    /**
     * Prints ready status to output stream
     */
    protected function statusReady()
    {
        $this->write(self::READY);
    }

    /**
     * Does the actual event handling
     *
     * @param EventInterface $event
     *
     * @return integer 0=success, 1=failure
     */
    abstract protected function doListen(EventInterface $event);

    /**
     * Parses colon devided data
     *
     * @param string $rawData
     *
     * @return array
     */
    protected function parseData($rawData)
    {
        $outputData = array();

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
     * @return integer Bytes written
     */
    protected function write($value)
    {
        return @fwrite($this->outputStream, $value);
    }

    /**
     * {@inheritdocs}
     *
     * @codeCoverageIgnore
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
