<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Connector;

use Indigo\Supervisor\Exception\SupervisorException;
use UnexpectedValueException;

/**
 * Abstract XMLRPC Connector
 *
 * Uses XMLRPC extension to parse messages
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractXmlrpcConnector extends AbstractConnector
{
    /**
     * Prepares XML request body
     *
     * @param string $namespace
     * @param string $method
     * @param array  $arguments
     *
     * @return string
     */
    public function prepareBody($namespace, $method, array $arguments = array())
    {
        return xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));
    }

    /**
     * Processes response message
     *
     * @param string $response
     *
     * @return string
     */
    public function processResponse($response)
    {
        $response = xmlrpc_decode(trim($response), 'utf-8');

        if (empty($response)) {
            throw new UnexpectedValueException('Invalid or empty response');
        } elseif (is_array($response) and xmlrpc_is_fault($response)) {
            throw new SupervisorException($response['faultString'], $response['faultCode']);
        }

        return $response;
    }
}
