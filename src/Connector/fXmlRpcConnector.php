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
use fXmlRpc\ClientInterface as Client;
use fXmlRpc\Exception\ResponseException;

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
     * @param Client  $client
     * @param boolean $local
     */
    public function __construct(Client $client, $local = false)
    {
        $this->setClient($client, $local);
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
     * @param Client  $client
     * @param boolean $local
     *
     * @return self
     */
    public function setClient(Client $client, $local = false)
    {
        $this->client = $client;
        $this->local = (bool) $local;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = [])
    {
        try {
            return $this->client->call($namespace.'.'.$method, $arguments);
        } catch (ResponseException $e) {
            throw new SupervisorException($e->getFaultString(), $e->getFaultCode(), $e);
        }
    }
}
