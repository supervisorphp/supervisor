<?php

namespace Indigo\Supervisor\Test\Connector;

use Indigo\Supervisor\Connector\InetSocketConnector;

/**
 * @coversDefaultClass \Indigo\Supervisor\Connector\InetSocketConnector
 */
class InetSocketConnectorTest extends SocketConnectorTest
{
    public function setUp()
    {
        $this->connector = new InetSocketConnector('google.hu', 80);
    }

    /**
     * @covers ::__construct
     * @covers ::isConnected
     * @group  Supervisor
     */
    public function testInstance()
    {
        $connector = new InetSocketConnector('google.hu', 80);

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Connector\\InetSocketConnector',
            $connector
        );

        $this->assertTrue($this->connector->isConnected());
    }
}
