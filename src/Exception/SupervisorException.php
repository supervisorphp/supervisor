<?php

namespace Supervisor\Exception;

use fXmlRpc\Exception\FaultException;

/**
 * Fault codes are taken from the source code, not the documentation.
 * The most common ones are covered by the XML-RPC doc.
 *
 * @link http://supervisord.org/api.html
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class SupervisorException extends \RuntimeException
{
    /**
     * Creates a new Fault exception if a named one from the table above is present.
     */
    public static function create(FaultException $faultException): FaultException|SupervisorException
    {
        $faultCode = $faultException->getFaultCode();
        $faultString = $faultException->getFaultString();

        $faultEnum = FaultCodes::tryFrom($faultCode);

        if (null === $faultEnum) {
            return $faultException;
        }

        $faultClass = $faultEnum->getExceptionClass();
        return new $faultClass($faultString, $faultCode);
    }
}
