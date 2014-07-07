<?php

namespace Test\Unit;

use Codeception\TestCase\Test;
use Indigo\Supervisor\Connector\ZendConnector;

/**
 * Tests for Zend connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\ZendConnector
 */
class ZendTest extends AbstractConnectorTest
{
    public function _before()
    {
        $this->client = \Mockery::mock('Zend\\XmlRpc\\Client');

        $this->client->shouldReceive('getHttpClient->setAuth');

        $this->connector = new ZendConnector($this->client);
    }

    /**
     * @covers ::__construct
     * @group  Supervisor
     */
    public function testConstruct()
    {
        $connector = new ZendConnector($this->client);

        $this->assertSame($this->client, $connector->getClient());
    }

    /**
     * @covers ::setCredentials
     * @group  Supervisor
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
     * @group             Supervisor
     */
    public function testCallException()
    {
        $this->client->shouldReceive('call')
            ->andThrow('Zend\\XmlRpc\\Client\\Exception\\FaultException');

        $this->connector->call('asd', 'dsa');
    }
}
