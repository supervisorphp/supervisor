<?php

namespace Indigo\Supervisor\Test\Connector;

use Indigo\Supervisor\Connector\UnixSocketConnector;

/**
 * Tests for Inet Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\UnixSocketConnector
 */
class UnixSocketConnectorTest extends SocketConnectorTest
{
    public function setUp()
    {
        try {
            $this->connector = new UnixSocketConnector($GLOBALS['socket']);
        } catch (\RuntimeException $e) {
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
