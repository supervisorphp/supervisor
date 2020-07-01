<?php

namespace Supervisor\Connector;

use fXmlRpc\ClientInterface;
use fXmlRpc\Exception\FaultException;
use Supervisor\Connector;
use Supervisor\Exception\Fault;

/**
 * Basic XML-RPC Connector using fXmlRpc.
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class XmlRpc implements Connector
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function call($namespace, $method, array $arguments = [])
    {
        try {
            return $this->client->call($namespace . '.' . $method, $arguments);
        } catch (FaultException $e) {
            throw Fault::create($e->getFaultString(), $e->getFaultCode());
        }
    }
}
