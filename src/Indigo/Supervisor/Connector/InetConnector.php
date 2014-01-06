<?php

namespace Indigo\Supervisor\Connector;

/**
 * Connect to Supervisor using simple file_get_contents
 * allow_url_fopen must be enabled
 */
class InetConnector extends AbstractConnector
{
    public function __construct($host, $port = 9001)
    {
        if (!preg_match("#^(http|https)://#i", $host)) {
            $host = 'http://' . $host;
        }

        $resource = parse_url($host);

        if (! $resource) {
            throw new \InvalidArgumentException('The following host is not a valid resource:' . $host);
        }

        $resource['port'] = $port;

        $this->resource = http_build_url(
            '/RPC2',
            $resource,
            HTTP_URL_REPLACE | HTTP_URL_STRIP_AUTH | HTTP_URL_STRIP_QUERY | HTTP_URL_STRIP_FRAGMENT
        );
    }

    public function isConnected()
    {
        return ! empty($this->resource);
    }

    public function call($namespace, $method, array $arguments = array())
    {
        if (!$this->isConnected()) {
            throw new \RuntimeException('Connection dropped');
        }

        $request = $this->prepareRequest($namespace, $method, $arguments);

        $response = $this->response($request);

        if (!$response) {
            $this->resource = null;
            throw new \RuntimeException('Connection dropped');
        }

        return $this->processResponse($response);
    }

    protected function prepareRequest($namespace, $method, array $arguments)
    {
        return xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));
    }

    protected function response($request)
    {
        $options = array(
            'http' => array(
                'method' => 'POST',
                'header' => http_build_headers($this->headers),
                'content' => $request
            )
        );

        $context  = stream_context_create($options);

        return @file_get_contents($this->resource, false, $context);
    }
}
