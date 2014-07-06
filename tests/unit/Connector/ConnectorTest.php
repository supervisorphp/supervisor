<?php

namespace Test\Unit;

use Codeception\TestCase\Test;
use Indigo\Supervisor\Connector\DummyConnector;

/**
 * Tests for Xmlrpc connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\AbstractConnector
 */
class ConnectorTest extends Test
{
    /**
     * Dummy Connector class
     *
     * @var DummyConnector
     */
    protected $connector;

    public function _before()
    {
        $this->connector = new DummyConnector;
    }

    /**
     * @covers ::setCredentials
     * @group  Supervisor
     */
    public function testCredentials()
    {
        $this->assertSame($this->connector, $this->connector->setCredentials('user', 123));
    }

    /**
     * @covers ::isLocal
     * @group  Supervisor
     */
    public function testIsLocal()
    {
        $this->assertTrue($this->connector->isLocal());
    }
}
