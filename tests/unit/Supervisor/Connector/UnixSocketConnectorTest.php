<?php

namespace Indigo\Supervisor\Connector;

/**
 * Tests for Unix Socket Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\UnixSocketConnector
 */
class UnixSocketConnectorTest extends AbstractSocketConnectorTest
{
    public function _before()
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
