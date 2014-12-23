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
 * Util class for configuration
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Util
{
    /**
     * Checks whether a given value is a valid byte value
     *
     * @param string|integer $value
     *
     * @return boolean
     */
    public static function isByte($value)
    {
        return is_numeric($value) or (is_string($value) and preg_match('/[0-9]+kb|mb|gb/i', $value));
    }
}
