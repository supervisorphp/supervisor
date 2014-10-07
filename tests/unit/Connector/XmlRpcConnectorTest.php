<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Unit;

use Indigo\Supervisor\Connector\XmlRpcConnector;

/**
 * Tests for fXmlRpc Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\XmlRpcConnector
 * @group              Supervisor
 * @group              Connector
 */
class XmlRpcConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $this->client = \Mockery::mock('fXmlRpc\\ClientInterface');

        $this->connector = new XmlRpcConnector($this->client, true);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $connector = new XmlRpcConnector($this->client);

        $this->assertFalse($connector->isLocal());
    }

    /**
     * @covers            ::call
     * @expectedException Indigo\Supervisor\Exception\SupervisorException
     */
    public function testCallException()
    {
        $this->client->shouldReceive('call')
            ->andThrow('fXmlRpc\\Exception\\ResponseException');

        parent::testCallException();
    }
}
