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

use Buzz\Client\ClientInterface;
use Buzz\Message\Factory\FactoryInterface;
use Buzz\Message\Factory\Factory as MessageFactory;
use Buzz\Message\RequestInterface;
use Buzz\Message\MessageInterface;
use Buzz\Exception\ClientException;
use Indigo\Supervisor\Exception\SupervisorException;
use UnexpectedValueException;

/**
 * Request Connector class
 *
 * Uses HTTP messages and xmlrp extension
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Request extends AbstractConnector
{
    /**
     * Client object
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Message Factory
     *
     * @var FactoryInterface
     */
    protected $messageFactory;

    /**
     * Optional host property
     *
     * Used with inet connections
     *
     * @var string
     */
    protected $host;

    /**
     * Creates new Request connector
     *
     * @param ClientInterface  $client
     * @param FactoryInterface $messageFactory
     */
    public function __construct(ClientInterface $client, FactoryInterface $messageFactory = null)
    {
        if ($messageFactory === null) {
            $messageFactory = new MessageFactory;
        }

        $this->client = $client;
        $this->messageFactory = $messageFactory;
    }

    /**
     * Returns the client
     *
     * @return ClientInterface
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets the client
     *
     * @param ClientInterface $client
     *
     * @return this
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Returns the FactoryInterface
     *
     * @return FactoryInterface
     */
    public function getMessageFactory()
    {
        return $this->messageFactory;
    }

    /**
     * Sets the FactoryInterface
     *
     * @param FactoryInterface $messageFactory
     *
     * @return this
     */
    public function setMessageFactory(FactoryInterface $messageFactory)
    {
        $this->messageFactory = $messageFactory;

        return $this;
    }

    /**
     * Returns the host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Sets the host
     *
     * @param string  $host
     * @param integer $port
     *
     * @return this
     */
    public function setHost($host, $port = 9001)
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = array())
    {
        $content = $this->prepareContent($namespace, $method, $arguments);
        $request = $this->messageFactory->createRequest();
        $this->prepareRequest($request, $content);

        $response = $this->messageFactory->createResponse();
        $this->client->send($request, $response);

        if ($response->isOk() === false) {
            throw new ClientException(
                'HTTP Status: ' . $response->getReasonPhrase(),
                $response->getStatusCode()
            );
        }

        return $this->processResponse($response);
    }

    /**
     * Generate XML request content
     *
     * @param string $namespace
     * @param string $method
     * @param array  $arguments
     *
     * @return string
     *
     * @codeCoverageIgnore
     */
    public function prepareContent($namespace, $method, array $arguments)
    {
        return xmlrpc_encode_request($namespace . '.' . $method, $arguments, array('encoding' => 'utf-8'));
    }

    /**
     * Prepare request with content
     *
     * @param RequestInterface $request
     * @param string           $content
     */
    public function prepareRequest(RequestInterface $request, $content)
    {
        // Default headers
        $headers = array(
            'Content-Type' => 'text/xml',
            'Content-Length' => strlen($xml),
        );

        // Add authentication to headers
        if (isset($this->username))
        {
            $headers['Authorization'] = 'Basic ' . base64_encode($username . ':' . $password);
        }

        if (isset($this->host))
        {
            $request->setHost($this->host);
        }

        // Set request specific data and content
        $request->setMethod(Request::METHOD_POST);
        $request->setHeaders($headers);
        $request->setResource('/RPC2');
        $request->setContent($content);
    }

    /**
     * Process response
     *
     * @param MessageInterface $response
     *
     * @return string
     */
    public function processResponse(MessageInterface $response)
    {
        $response = xmlrpc_decode(trim($response), 'utf-8');

        if (!$response) {
            throw new UnexpectedValueException('Invalid or empty response');
        } elseif (is_array($response) and xmlrpc_is_fault($response)) {
            throw new SupervisorException($response['faultString'], $response['faultCode']);
        }

        return $response;
    }
}
