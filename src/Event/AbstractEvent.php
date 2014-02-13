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

/**
 * Abstract Event
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractEvent implements EventInterface
{
    /**
     * Header values
     *
     * @var array
     */
    protected $header = array();

    /**
     * Payload values
     *
     * @var array
     */
    protected $payload = array();

    /**
     * Body
     *
     * @var string
     */
    protected $body = null;

    public function __construct(array $header, array $payload, $body = null)
    {
        $this->setHeader($header);
        $this->setPayload($payload);
        $this->setBody($body);
    }

    /**
     * {@inheritdoc}
     */
    public function getHeader($key = null, $default = null)
    {
        return $this->arrGet($this->header, $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function setHeader(array $header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getPayload($key = null, $default = null)
    {
        return $this->arrGet($this->payload, $key, $default);
    }

    /**
     * {@inheritdoc}
     */
    public function setPayload(array $payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get specific or all items from array
     * Return default if key not found
     *
     * @param  array       $array
     * @param  string|null $key
     * @param  mixed       $default
     * @return mixed
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
