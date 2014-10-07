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

/**
 * Implements details for Configuration sections
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Section
{
    /**
     * Gets the section name
     * Should be set explicitly for single sections (eg. supervisord)
     *
     * @return string
     */
    public function getName();

    /**
     * Returns the options as an array
     *
     * @return []
     */
    public function getOptions();

    /**
     * Sets an array of options
     *
     * @param [] $options
     *
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Checks whether section has any options
     *
     * @return boolean
     */
    public function hasOptions();
}
