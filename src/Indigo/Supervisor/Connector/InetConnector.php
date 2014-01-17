<?php

namespace Indigo\Supervisor\Connector;

use Buzz\Message\Request;
use Buzz\Client\FileGetContents as Client;

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

        if (!$resource) {
            throw new \InvalidArgumentException('The following host is not a valid resource:' . $host);
        }

        $resource['port'] = $port;

        $this->resource = http_build_url(
            '',
            $resource,
            HTTP_URL_REPLACE | HTTP_URL_STRIP_AUTH | HTTP_URL_STRIP_QUERY | HTTP_URL_STRIP_FRAGMENT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isConnected()
    {
        return ! empty($this->resource);
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareRequest($namespace, $method, array $arguments)
    {
        // generate xml request
        $xml = xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));

        // add length to headers
        $headers = array_merge($this->headers, array('Content-Length' => strlen($xml)));

        $request = new Request('POST', '/RPC2', $this->resource);
        $request->setProtocolVersion(1.1);
        $request->setHeaders($headers);
        $request->setContent($xml);

        return $request;
    }

    /**
     * {@inheritdoc}
     */
    protected function prepareClient()
    {
        return new Client();
    }
}
