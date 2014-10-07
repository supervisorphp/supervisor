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

use Indigo\Supervisor\Connector\ZendConnector;

/**
 * Tests for Zend Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\ZendConnector
 * @group              Supervisor
 * @group              Connector
 */
class ZendConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $this->client = \Mockery::mock('Zend\\XmlRpc\\Client');

        $this->connector = new ZendConnector($this->client, true);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $connector = new ZendConnector($this->client);

        $this->assertFalse($connector->isLocal());
    }

    /**
     * @covers            ::call
     * @expectedException Indigo\Supervisor\Exception\SupervisorException
     */
    public function testCallException()
    {
        $this->client->shouldReceive('call')
            ->andThrow('Zend\\XmlRpc\\Client\\Exception\\FaultException');

        parent::testCallException();
    }
}
