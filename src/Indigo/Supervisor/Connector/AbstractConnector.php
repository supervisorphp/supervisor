<?php

namespace Indigo\Supervisor\Connector;

use Indigo\Supervisor\Exception\ResponseException;

abstract class AbstractConnector implements ConnectorInterface
{
    /**
     * Resource used for connection (URL, socket, etc)
     *
     * @var mixed
     */
    protected $resource;

    /**
     * Headers to send
     *
     * @var array
     */
    protected $headers = array(
        'Content-Type' => 'text/xml'
    );

    /**
     * Set credentials for connection and set header
     *
     * @param  string             $username
     * @param  string             $password
     * @return ConnectorInterface
     */
    public function setCredentials($username, $password)
    {
        return $this->setHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $password));
    }

    /**
     * Get HTTP header(s)
     *
     * @param  string $name Header name
     * @return mixed  One specific value or all headers
     */
    public function getHeader($name = null)
    {
        if (is_null($name)) {
            return $this->headers;
        } elseif (array_key_exists($name, $this->headers)) {
            return $this->headers[$name];
        }
    }

    /**
     * Set a HTTP header for request
     *
     * @param  string             $name    Header name
     * @param  string             $value   Header value
     * @param  boolean            $replace Replace header if already exists
     * @return ConnectorInterface
     */
    public function setHeader($name, $value, $replace = true)
    {
        if ($replace) {
            $this->headers[$name] = $value;
        } elseif (array_key_exists($name, $this->headers)) {
            if (!is_array($this->headers[$name])) {
                $this->headers[$name] = array($this->headers[$name]);
            }

            $this->headers[$name][] = $value;
        } else {
            $this->headers[$name] = $value;
        }

        return $this;
    }

    /**
     * Get resource
     *
     * @return mixed
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Set resource
     * Validation of resource (if needed) is up to the class itself
     *
     * @param  mixed              $resource
     * @return ConnectorInterface
     */
    public function setResource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    /**
     * Process HTTP response
     *
     * @param  string $response Raw response
     * @return mixed
     */
    protected function processResponse($response)
    {
        $response = xmlrpc_decode(trim($response), 'utf-8');

        if (!$response) {
            throw new \UnexpectedValueException('Invalid or empty response');
        } elseif (is_array($response) and xmlrpc_is_fault($response)) {
            throw new ResponseException($response['faultString'], $response['faultCode']);
        }

        return $response;
    }

    /**
     * Prepare request
     *
     * @param  string $namespace
     * @param  string $method
     * @param  array  $arguments
     * @return mixed
     */
    abstract protected function prepareRequest($namespace, $method, array $arguments);
}
