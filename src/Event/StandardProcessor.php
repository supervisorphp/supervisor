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
use InvalidArgumentException;

/**
 * Processor for standard IO streams
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class StandardProcessor implements Processor
{
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
     * Event Emitter
     *
     * @var EmitterInterface
     */
    protected $emitter;

    /**
     * @param EmitterInterface $emitter
     */
    public function __construct(EmitterInterface $emitter)
    {
        $this->emitter = $emitter;
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
     * Sets the input stream
     *
     * @param resource $stream
     *
     * @return self
     */
    public function setInputStream($stream)
    {
        $this->assertValidStreamResource($stream);

        $this->inputStream = $stream;

        return $this;
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
     * Sets the output stream
     *
     * @param resource $stream
     *
     * @return self
     */
    public function setOutputStream($stream)
    {
        $this->assertValidStreamResource($stream);

        $this->outputStream = $stream;

        return $this;
    }

    /**
     * Asserts that a given input is a valid stream resource
     *
     * @param resource $stream
     *
     * @throws InvalidArgumentException If $stream is not a valid resource
     */
    private function assertValidStreamResource($stream)
    {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException('Invalid resource for IO stream');
        }
    }

    /**
     * {@inheritdoc}
     *
     * @codeCoverageIgnore
     */
    public function run()
    {
        while (true) {
            $this->write(self::READY);

            if ($event = $this->getEvent()) {
                $this->emitter->emit($event);
                $this->processResult($event);

                if ($event->shouldProcessorStop()) {
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
        if ($header = $this->read()) {
            $header = $this->parseData($header);

            $payload = $this->read($header['len']);
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
     * @param Event $event Emitted event
     */
    protected function processResult(Event $event)
    {
        $result = $event->getResult();

        if (is_null($result)) {
            $result = self::FAIL;
        }

        $this->write($result);
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
}
