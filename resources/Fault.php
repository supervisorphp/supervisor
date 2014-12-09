<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Exception;

/**
 * Fault codes are taken from the source code, not the documentation
 * The most common ones are covered by the XML-RPC doc
 *
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Fault extends \Exception
{
    /**
     * Fault responses
     */
{CONSTANTS}

    /**
     * @var array
     */
    private static $exceptionMap = [
{EXCEPTION_MAP}
    ];

    /**
     * Creates a new Fault
     *
     * If there is a mach for the fault code in the exception map then the matched exception will be returned
     *
     * @param string  $faultString
     * @param integer $faultCode
     *
     * @return self
     */
    public static function create($faultString, $faultCode)
    {
        if (!isset(self::$exceptionMap[$faultCode])) {
            return new self($faultString, $faultCode);
        }

        return new self::$exceptionMap[$faultCode];
    }
}
