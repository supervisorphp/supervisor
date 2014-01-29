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

use Buzz\Message\Response;
use Buzz\Exception\ClientException;
use Indigo\Supervisor\Exception\SupervisorException;
use UnexpectedValueException;

/**
 * Abstract Connector class
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
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
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = array())
    {
        $request = $this->prepareRequest($namespace, $method, $arguments);

        $response = new Response();
        $client = $this->prepareClient();
        $client->send($request, $response);

        if (!$response->isOk()) {
            throw new ClientException(
                'HTTP Status: ' . $response->getReasonPhrase(),
                $response->getStatusCode()
            );
        }

        return $this->processResponse($response->getContent());
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
            throw new UnexpectedValueException('Invalid or empty response');
        } elseif (is_array($response) and xmlrpc_is_fault($response)) {
            throw new SupervisorException($response['faultString'], $response['faultCode']);
        }

        return $response;
    }

    /**
     * Prepare request
     *
     * @param  string                        $namespace
     * @param  string                        $method
     * @param  array                         $arguments
     * @return Buzz\Message\RequestInterface
     */
    abstract protected function prepareRequest($namespace, $method, array $arguments);

    /**
     * Prepare client
     *
     * @return Buzz\Client\ClientInterface
     */
    abstract protected function prepareClient();
}
