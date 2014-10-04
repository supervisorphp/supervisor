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

use Indigo\Supervisor\Connector\fXmlRpcConnector;

/**
 * Tests for Zend Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\fXmlRpcConnector
 * @group              Supervisor
 * @group              Connector
 */
class fXmlRpcConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $this->client = \Mockery::mock('fXmlRpc\\ClientInterface');
        $this->client->transport = \Mockery::mock('fXmlRpc\\Transport\\TransportInterface');
        $this->client->transport->shouldReceive('setHeader');

        $this->connector = new fXmlRpcConnector($this->client);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $connector = new fXmlRpcConnector($this->client);

        $this->assertSame($this->client, $connector->getClient());
    }

    /**
     * @covers ::setCredentials
     */
    public function testCredentials()
    {
        $this->assertSame(
            $this->connector,
            $this->connector->setCredentials('user', '123')
        );
    }

    /**
     * @covers            ::call
     * @expectedException Indigo\Supervisor\Exception\SupervisorException
     */
    public function testCallException()
    {
        $this->client->shouldReceive('call')
            ->andThrow('fXmlRpc\\Exception\\ResponseException');

        $this->connector->call('asd', 'dsa');
    }
}
