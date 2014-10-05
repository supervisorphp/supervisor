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

use Indigo\Supervisor\Event;
use League\Event\EmitterInterface;
use GuzzleHttp\Stream\StreamInterface;
use GuzzleHttp\Stream\read_line;

/**
 * Processor for guzzle streams
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class GuzzleStreamProcessor implements Processor
{
    /**
     * Input stream
     *
     * @var StreamInterface
     */
    protected $inputStream;

    /**
     * Output stream
     *
     * @var StreamInterface
     */
    protected $outputStream;

    /**
     * Event Emitter
     *
     * @var EmitterInterface
     */
    protected $emitter;

    /**
     * @param EmitterInterface $emitter
     */
    public function __construct(
        StreamInterface $inputStream,
        StreamInterface $outputStream,
        EmitterInterface $emitter
    )
    {
        $this->emitter = $emitter;
        $this->inputStream = $inputStream;
        $this->outputStream = $outputStream;
    }

    /**
     * Returns the input stream
     *
     * @return StreamInterface
     */
    public function getInputStream()
    {
        return $this->inputStream;
    }

    /**
     * Sets the input stream
     *
     * @param StreamInterface $stream
     *
     * @return self
     */
    public function setInputStream(StreamInterface $stream)
    {
        $this->inputStream = $stream;

        return $this;
    }

    /**
     * Returns the output stream
     *
     * @return StreamInterface
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * Sets the output stream
     *
     * @param StreamInterface $stream
     *
     * @return self
     */
    public function setOutputStream(StreamInterface $stream)
    {
        $this->outputStream = $stream;

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function run()
    {
        while (true) {
            $this->statusReady();

            if ($event = $this->getEvent()) {
                $this->emitter->emit($event);

                if ($this->processResult($event) === false) {
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
        if ($header = read_line($this->inputStream)) {
            $header = $this->parseData($header);

            $payload = $this->inputStream->read($header['len']);
            $payload = explode("\n", $payload, 2);
            isset($payload[1]) or $payload[1] = null;

            list($payload, $body) = $payload;

            $payload = $this->parseData($payload);

            return new Event($header, $payload, $body);
        }
    }

    /**
     * Processes result
     *
     * @param integer $result Result code
     *
     * @return boolean Listener should exit or not
     */
    protected function processResult(Event $event)
    {
        switch ($result = $event->getResult()) {
            case self::QUIT:
                return false;
                break;
            case null:
                // No response should be treated as failure
                $this->outputStream->write(self::FAIL);
                break;
            default:
                $this->outputStream->write($result);
                break;
        }

        return true;
    }

    /**
     * Prints ready status to output stream
     */
    protected function statusReady()
    {
        $this->outputStream->write(self::READY);
    }

    /**
     * Parses colon devided data
     *
     * @param string $rawData
     *
     * @return []
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
}
