<?php

namespace Indigo\Supervisor\Connector;

class InetConnectorTest extends ConnectorTest
{
    public function setUp()
    {
        $this->connector = new InetConnector('localhost');
    }

    public function testInstance()
    {
        $connector = new InetConnector('localhost');

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Connector\\InetConnector',
            $connector
        );

        $this->assertTrue($this->connector->isConnected());
    }

    /**
     * @expectedException RuntimeException
     */
    public function testCall()
    {
        $this->connector->call('nothing', 'here');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testCallFailure()
    {
        $this->connector->setResource(null);
        $this->connector->call('nothing', 'here');
    }
}