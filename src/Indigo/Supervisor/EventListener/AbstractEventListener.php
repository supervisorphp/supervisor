<?php

namespace Indigo\Supervisor\EventListener;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

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
     * Set input stream
     *
     * @param resource $stream
     */
    public function setInputStream($stream)
    {
        if (is_resource($stream)) {
            $this->inputStream = $stream;
        } else {
            throw new \InvalidArgumentException('Invalid resource for input stream');
        }
    }

    /**
     * Set output stream
     *
     * @param resource $stream
     */
    public function setOutputStream($stream)
    {
        if (is_resource($stream)) {
            $this->inputStream = $stream;
        } else {
            throw new \InvalidArgumentException('Invalid resource for input stream');
        }
    }

    /**
     * Listen for events
     */
    public function listen()
    {
        $this->write(self::READY);

        while (true) {
            if ( ! $headers = $this->read()) {
                continue;
            }

            $headers = $this->parseData($headers);

            $payload = $this->read($headers['len']);

            $payload = explode("\n", $payload, 2);

            $payload[0] = array_merge($headers, $this->parseData($payload[0]));

            $result = $this->doListen($payload);

            if ($result === 0) {
                $this->write(self::OK);
            } elseif ($result === 1) {
                $this->write(self::FAIL);
            } else {
                return;
            }

            $this->write(self::READY);
        }
    }

    /**
     * Do the actual event handling
     *
     * @param  array   $payload
     * @return integer          0=success, 1=failure, 2=quit
     */
    abstract protected function doListen(array $payload);

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
     * @param  string $value
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
