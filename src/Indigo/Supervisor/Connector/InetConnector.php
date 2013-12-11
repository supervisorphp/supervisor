<?php

namespace Indigo\Supervisor\Connector;

use Indigo\Supervisor\Exception\InvalidArgumentException;

class InetConnector extends AbstractConnector
{
	public function __construct($host, $port = 9001) {
        if( ! preg_match("#^(http|https)://#i", $host))
        {
            $host = 'http://' . $host;
        }

		$resource = parse_url($host);

		if ( ! $resource) {
			throw new InvalidArgumentException('The following host is not a valid resource:' . $host);
		}

		$resource['port'] = $port;

		$this->resource = http_build_url('/RPC2', $resource, HTTP_URL_STRIP_AUTH | HTTP_URL_STRIP_QUERY | HTTP_URL_STRIP_FRAGMENT);
	}

	public function call($namespace, $method, array $arguments = array())
	{
		$request = xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));

		$headers = array();

        foreach ($this->headers as $key => $value) {
        	$headers[] = "$key: $value";
        }

        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => $headers,
                'content' => $request
            )
        );

        $context  = stream_context_create($options);
        $response = file_get_contents($this->resource, false, $context);

        return $this->processResponse($response);
	}
}
