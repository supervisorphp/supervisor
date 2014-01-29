<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\EventListener;

use Indigo\Supervisor\Event\Event;
use Indigo\Supervisor\Event\EventInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

/**
 * Abstract EventListener
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractEventListener implements EventListenerInterface, LoggerAwareInterface
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
     * {@inheritdoc}
     */
    public function getInputStream()
    {
        return $this->inputStream;
    }

    /**
     * {@inheritdoc}
     */
    public function setInputStream($stream)
    {
        if (is_resource($stream)) {
            $this->inputStream = $stream;
        } else {
            throw new \InvalidArgumentException('Invalid resource for input stream');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * {@inheritdoc}
     */
    public function setOutputStream($stream)
    {
        if (is_resource($stream)) {
            $this->outputStream = $stream;
        } else {
            throw new \InvalidArgumentException('Invalid resource for output stream');
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function listen()
    {
        while (true) {
            $this->statusReady();

            if (!$event = $this->getEvent()) {
                continue;
            }

            $result = $this->doListen($event);

            if (!$this->processResult($result)) {
                return;
            }
        }
    }

    /**
     * Get event from input stream
     *
     * @return Event|false Event object
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
     * Resolve EventInterface
     *
     * @param  array          $header
     * @param  array          $payload
     * @param  string         $body
     * @return EventInterface
     */
    protected function resolveEvent(array $header, array $payload, $body = null)
    {
        return new Event($header, $payload, $body);
    }

    /**
     * Process result
     *
     * @param  integer $result Result code
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
     * Print ready status to output stream
     */
    protected function statusReady()
    {
        $this->write(self::READY);
    }

    /**
     * Do the actual event handling
     *
     * @param  EventInterface $event
     * @return integer        0=success, 1=failure
     */
    abstract protected function doListen(EventInterface $event);

    /**
     * Parse colon devided data
     *
     * @param  string $rawData
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
     * Read data from input stream
     *
     * @param  integer $length If given read this size of bytes, read a line anyway
     * @return string
     */
    protected function read($length = null)
    {
        if (is_null($length)) {
            return trim(fgets($this->inputStream));
        } else {
            return fread($this->inputStream, $length);
        }
    }

    /**
     * Write data to output stream
     *
     * @param string $value
     */
    protected function write($value)
    {
        return @fwrite($this->outputStream, $value);
    }

    /**
     * Sets a logger
     *
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
