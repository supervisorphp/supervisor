<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) IndigoPHP Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event;

abstract class AbstractEvent implements EventInterface
{
    protected $headers = array();
    protected $payload = array();
    protected $body = null;

    public function __construct(array $headers = array(), array $payload = array(), $body = null)
    {
        $this->setHeader($headers);
        $this->setPayload($payload);
        $this->setBody($body);
    }

    public function getHeader($key = null, $default = null)
    {
        $this->arrGet($this->headers, $key, $default);
    }

    public function setHeader(array $headers)
    {
        $this->headers = $headers;
    }

    public function getPayload($key = null, $default = null)
    {
        $this->arrGet($this->payload, $key, $default);
    }

    public function setPayload(array $payload)
    {
        $this->payload = $payload;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

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
