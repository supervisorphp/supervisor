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

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\MessageInterface;
use GuzzleHttp\Stream;
use GuzzleHttp\Stream\StreamInterface;

/**
 * Guzzle connector class
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class GuzzleConnector extends AbstractXmlrpcConnector
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
     * {@inheritdocs}
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
     * {@inheritdocs}
     *
     * @return StreamInterface
     */
    public function prepareBody($namespace, $method, array $arguments = array())
    {
        return Stream\create(parent::prepareBody($namespace, $method, $arguments));
    }

    /**
     * Prepare request with body
     *
     * @param RequestInterface $request
     * @param StreamInterface  $body
     */
    public function prepareRequest(RequestInterface $request, StreamInterface $body)
    {
        // Default headers
        $headers = array(
            'Content-Type' => 'text/xml',
            'Content-Length' => $body->getSize(),
        );

        // Add authentication to headers
        if (isset($this->username)) {
            $headers['Authorization'] = 'Basic ' . base64_encode($this->username . ':' . $this->password);
        }

        // Set request specific data and body
        $request->setMethod('POST')
            ->setHeaders($headers)
            ->setPath('/RPC2')
            ->setBody($body);
    }
}
