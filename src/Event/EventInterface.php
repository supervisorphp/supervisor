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
     * Returns a specific or all header keys
     * Returns default if key not found
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function getHeader($key = null, $default = null);

    /**
     * Sets header values
     *
     * @param array $header
     *
     * @return this
     */
    public function setHeader(array $header);

    /**
     * Returns a specific or all payload keys
     * Returns default if key not found
     *
     * @param string|null $key
     * @param mixed       $default
     *
     * @return mixed
     */
    public function getPayload($key = null, $default = null);

    /**
     * Sets the payload values
     *
     * @param array $payload
     *
     * @return this
     */
    public function setPayload(array $payload);

    /**
     * Returns the body
     *
     * @return string
     */
    public function getBody();

    /**
     * Sets the body
     *
     * @param string $body
     *
     * @return this
     */
    public function setBody($body);
}
