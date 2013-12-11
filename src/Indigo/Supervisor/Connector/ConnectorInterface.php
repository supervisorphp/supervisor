<?php

namespace Indigo\Supervisor\Connector;

interface ConnectorInterface
{
	public function call($namespace, $method, array $arguments = array());
}