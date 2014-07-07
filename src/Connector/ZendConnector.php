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

use Zend\XmlRpc\Client;
use Zend\Http\Client as HttpClient;
use Zend\XmlRpc\Client\Exception\FaultException;
use Indigo\Supervisor\Exception\SupervisorException;

/**
 * Zend connector class
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class ZendConnector extends AbstractConnector
{
    /**
     * Client object
     *
     * @var Client
     */
    protected $client;

    /**
     * Creates new Zend connector
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    /**
     * {@inheritdocs}
     */
    public function setCredentials($username, $password)
    {
        $this->client->getHttpClient()->setAuth($username, $password, HttpClient::AUTH_BASIC);

        return parent::setCredentials($username, $password);
    }

    /**
     * Returns the client
     *
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Sets the client
     *
     * @param Client $client
     *
     * @return this
     */
    public function setClient(Client $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = array())
    {
        try {
            return $this->client->call($namespace.'.'.$method, $arguments);
        } catch (FaultException $e) {
            throw new SupervisorException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
