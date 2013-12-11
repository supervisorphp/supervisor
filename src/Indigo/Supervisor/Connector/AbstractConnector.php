<?php

namespace Indigo\Supervisor\Connector;

use Indigo\Supervisor\Exception\ResponseException;
use Indigo\Supervisor\Exception\InvalidResponseException;

abstract class AbstractConnector implements ConnectorInterface
{
    protected $resource;

    protected $username;

    protected $password;

    protected $headers = array(
        'Content-Type' => 'text/xml'
    );

    public function setCredentials($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->setHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $password));
    }

    public function setHeader($name, $value)
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function processResponse($response)
    {
        $response = xmlrpc_decode(trim($response), 'utf-8');

        if ( ! $response) {
            throw new InvalidResponseException('Invalid or empty response');
        } elseif (is_array($response) and xmlrpc_is_fault($response)) {
            throw new ResponseException($response['faultString'], $response['faultCode']);
        }

        return $response;
    }
}