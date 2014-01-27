<?php

namespace Indigo\Supervisor\Test\Connector;

use Indigo\Supervisor\Connector\InetConnector;

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

    public function testResource()
    {
        $connector = clone $this->connector;
        $this->assertInstanceOf(
            get_class($connector),
            $connector->setResource(null)
        );

        $this->assertNull($connector->getResource());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidResource()
    {
        $connector = new InetConnector('xyz:\\\\_.?');
    }

    /**
     * @expectedException RuntimeException
     */
    public function testFaultyCall()
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
