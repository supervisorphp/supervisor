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
 * Event Interface
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface EventInterface
{
    /**
     * Get a specific or all header keys
     * Return default if key not found
     *
     * @param  string|null $key
     * @param  mixed       $default
     * @return mixed
     */
    public function getHeader($key = null, $default = null);

    /**
     * Set header values
     *
     * @param  array          $header
     * @return EventInterface
     */
    public function setHeader(array $header);

    /**
     * Get a specific or all payload keys
     * Return default if key not found
     *
     * @param  string|null $key
     * @param  mixed       $default
     * @return mixed
     */
    public function getPayload($key = null, $default = null);

    /**
     * Set payload values
     *
     * @param  array          $payload
     * @return EventInterface
     */
    public function setPayload(array $payload);

    /**
     * Get body
     *
     * @return string
     */
    public function getBody();

    /**
     * Set body
     *
     * @param  string         $body
     * @return EventInterface
     */
    public function setBody($body);
}
