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

use Indigo\Supervisor\Exception\SupervisorException;
use fXmlRpc\ClientInterface;
use fXmlRpc\Exception\ResponseException;
use ReflectionProperty;

/**
 * fxmlrpc Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class fXmlRpcConnector extends AbstractConnector
{
    /**
     * Client object
     *
     * @var Client
     */
    protected $client;

    /**
     * Creates new fXmlRpc connector
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
     * @return self
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setCredentials($username, $password)
    {
        // Ugly hack until transport problem is solved
        $prop = new ReflectionProperty('fXmlRpc\\Client', 'transport');
        $prop->setAccessible(true);
        $transport = $prop->getValue($this->client);

        $transport->setHeader('Authorization', 'Basic ' . base64_encode($username . ':' . $password));

        return parent::setCredentials($username, $password);
    }

    /**
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = array())
    {
        try {
            return $this->client->call($namespace.'.'.$method, $arguments);
        } catch (ResponseException $e) {
            throw new SupervisorException($e->getFaultString(), $e->getFaultCode(), $e);
        }
    }
}
