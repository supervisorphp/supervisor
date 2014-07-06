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

use Guzzle\Http\ClientInterface;
use Guzzle\Http\Message\RequestInterface;
use Guzzle\Http\Message\MessageInterface;
use Guzzle\Http\Stream;
use Guzzle\Http\Stream\StreamInterface;

/**
 * Guzzle3 connector class
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Guzzle3 extends AbstractXmlrpcConnector
{
    /**
     * Client object
     *
     * @var ClientInterface
     */
    protected $client;

    /**
     * Creates new Guzzle connector
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->setClient($client);
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

        $this->local = gethostbyname(parse_url($client->getBaseUrl(), PHP_URL_HOST)) === '127.0.0.1';

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = array())
    {
        $body = $this->prepareBody($namespace, $method, $arguments);
        $request = $this->client->createRequest('POST');
        $this->prepareRequest($request, $body);

        $response = $this->client->send($request);

        return $this->processResponse($response->getBody());
    }

    /**
     * Prepare request with body
     *
     * @param RequestInterface $request
     * @param string           $body
     */
    public function prepareRequest(RequestInterface $request, $body)
    {
        // Default headers
        $headers = array(
            'Content-Type' => 'text/xml',
            'Content-Length' => strlen($body),
        );

        // Add authentication to headers
        if (isset($this->username)) {
            $request->setAuth($this->username, $this->password);
        }

        // Set request specific data and body
        $request->addHeaders($headers)
            ->setPath('/RPC2')
            ->setBody($body);
    }
}
