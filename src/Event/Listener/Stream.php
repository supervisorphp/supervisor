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
use GuzzleHttp\Stream\StreamInterface;
use GuzzleHttp\Stream\Utils;

/**
 * Listener using guzzle streams
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Stream implements Listener
{
    /**
     * @var StreamInterface
     */
    protected $inputStream;

    /**
     * @var StreamInterface
     */
    protected $outputStream;

    /**
     * @param EmitterInterface $emitter
     */
    public function __construct(StreamInterface $inputStream, StreamInterface $outputStream)
    {
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
     * Returns the output stream
     *
     * @return StreamInterface
     */
    public function getOutputStream()
    {
        return $this->outputStream;
    }

    /**
     * {@inheritdoc}
     */
    public function listen(Handler $handler)
    {
        while (true) {
            $this->outputStream->write("READY\n");

            if ($notification = $this->getNotification()) {
                try {
                    $handler->handle($notification);
                    $this->outputStream->write("RESULT 2\nOK");
                } catch (EventHandlingFailed $e) {
                    $this->outputStream->write("RESULT 4\nFAIL");
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
        if ($header = trim(Utils::readLine($this->inputStream))) {
            $header = $this->parseData($header);

            $payload = $this->inputStream->read((int) $header['len']);
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
}
