<?php

namespace Indigo\Supervisor\Connector;

interface ConnectorInterface
{
	/**
	 * Send a new request to the XML-RPC server
	 *
	 * @param  string $namespace Namespace
	 * @param  string $method    Method
	 * @param  array  $arguments Optional arguments
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
}
