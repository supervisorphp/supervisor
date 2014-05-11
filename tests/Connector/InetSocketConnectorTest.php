<?php

namespace Indigo\Supervisor\Test\Connector;

use Indigo\Supervisor\Connector\InetSocketConnector;

/**
 * Tests for Inet Socket Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\InetSocketConnector
 */
class InetSocketConnectorTest extends SocketConnectorTest
{
    public function setUp()
    {
        $this->connector = new InetSocketConnector($GLOBALS['host'], $GLOBALS['port']);
    }

    /**
     * @covers ::__construct
     * @covers ::isConnected
     * @group  Supervisor
     */
    public function testInstance()
    {
        $connector = new InetSocketConnector($GLOBALS['host'], $GLOBALS['port']);

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Connector\\InetSocketConnector',
            $connector
        );

        $this->assertTrue($this->connector->isConnected());
    }
}
