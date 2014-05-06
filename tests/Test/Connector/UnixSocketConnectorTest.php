<?php

namespace Indigo\Supervisor\Test\Connector;

use Indigo\Supervisor\Connector\UnixSocketConnector;

/**
 * @coversDefaultClass \Indigo\Supervisor\Connector\UnixSocketConnector
 */
class UnixSocketConnectorTest extends SocketConnectorTest
{
    public function setUp()
    {
        $this->connector = new UnixSocketConnector($GLOBALS['socket']);

        if (!$this->connector->isConnected()) {
            $this->markTestSkipped(
                'Supervisor is not available.'
            );
        }
    }

    /**
     * @covers ::__construct
     * @covers ::isConnected
     * @group  Supervisor
     */
    public function testInstance()
    {
        $connector = new UnixSocketConnector($GLOBALS['socket']);

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Connector\\UnixSocketConnector',
            $connector
        );

        $this->assertTrue($this->connector->isConnected());
    }
}
