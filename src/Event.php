<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor;

use League\Event\Abstract\AbstractEvent;

/**
 * Supervisor Event
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Event extends AbstractEvent
{
    /**
     * Header values
     *
     * @var []
     */
    protected $header = [];

    /**
     * Payload values
     *
     * @var []
     */
    protected $payload = [];

    /**
     * Body
     *
     * @var string
     */
    protected $body = null;

    /**
     * Event result
     *
     * @var mixed
     */
    protected $result;

    /**
     * @param []     $header
     * @param []     $payload
     * @param string $body
     */
    public function __construct(array $header, array $payload, $body = null)
    {
        $this->setHeader($header);
        $this->setPayload($payload);
        $this->setBody($body);
    }

    /**
     * {@inheritdoc}
     */
    public function getName($name)
    {
        return $this->arrGet($this->header, 'eventname');
    }

    /**
     * Returns a specific or all header keys
     * Returns default if key not found
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function getHeader($key = null, $default = null)
    {
        return $this->arrGet($this->header, $key, $default);
    }

    /**
     * Sets header values
     *
     * @param [] $header
     *
     * @return self
     */
    public function setHeader(array $header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Returns a specific or all payload keys
     * Returns default if key not found
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function getPayload($key = null, $default = null)
    {
        return $this->arrGet($this->payload, $key, $default);
    }

    /**
     * Sets the payload values
     *
     * @param [] $payload
     *
     * @return self
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * Returns the body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Sets the body
     *
     * @param string $body
     *
     * @return self
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Returns the result
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Sets the result
     *
     * @param mixed $result
     *
     * @return self
     */
    public function setResult($result)
    {
        $this->result = $result;

        return $this;
    }

    /**
     * Get specific or all items from array
     * Return default if key not found
     *
     * @param []          $array
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    private function arrGet(array $array, $key = null, $default = null)
    {
        if (is_null($key)) {
            return $array;
        } elseif (array_key_exists($key, $array)) {
            return $array[$key];
        } else {
            return $default;
        }
    }
}
