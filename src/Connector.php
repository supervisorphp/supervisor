<?php

namespace Supervisor;

/**
 * Handles requests/responses to/from the Supervisor XML-RPC API.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Connector
{
    /**
     * Sends a new request to the XML-RPC server.
     *
     * @param string $namespace
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function call($namespace, $method, array $arguments = []);
}
