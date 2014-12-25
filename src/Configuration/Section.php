<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Configuration;

/**
 * Properties are grouped into sections
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Section
{
    /**
     * Returns the section name
     *
     * Should be set explicitly for single sections (eg. supervisord)
     *
     * @return string
     */
    public function getName();

    /**
     * Returns a specific property
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getProperty($key);

    /**
     * Sets a specific property
     *
     * @param string $key
     * @param mixed  $value
     */
    public function setProperty($key, $value);

    /**
     * Returns the properties as an array
     *
     * @return array
     */
    public function getProperties();

    /**
     * Sets an array of properties
     *
     * @param array $properties
     */
    public function setProperties(array $properties);
}
