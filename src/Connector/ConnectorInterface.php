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

/**
 * Connector Interface
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface ConnectorInterface
{
    /**
     * Send a new request to the XML-RPC server
     *
     * @param string $namespace Namespace
     * @param string $method    Method
     * @param array  $arguments Optional arguments
     *
     * @return mixed
     */
    public function call($namespace, $method, array $arguments = array());

    /**
     * Check whether connector is connected to the service
     * The return value should always return true, if a connection is present or available.
     *
     * @return boolean
     */
    public function isConnected();

    /**
     * Check whether connecting to a local Supervisor instance
     *
     * @return boolean
     */
    public function isLocal();
}
