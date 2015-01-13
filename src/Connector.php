<?php

/*
 * This file is part of the Supervisor package.
 *
 * (c) Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Supervisor;

/**
 * Handles requests/responses to/from the Supervisor XML-RPC API
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
interface Connector
{
    /**
     * Sends a new request to the XML-RPC server
     *
     * @param string $namespace
     * @param string $method
     * @param array  $arguments
     *
     * @return mixed
     */
    public function call($namespace, $method, array $arguments = []);
}
