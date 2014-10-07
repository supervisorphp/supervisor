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

use Codeception\TestCase\Test;

/**
 * Tests for Connectors
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractConnectorTest extends Test
{
    /**
     * HTTP/RPC Client
     *
     * @var object
     */
    protected $client;

    /**
     * Connector
     *
     * @var Indigo\Supervisor\Connector
     */
    protected $connector;

    /**
     * @covers ::isLocal
     */
    public function testIsLocal()
    {
        $this->assertTrue($this->connector->isLocal());
    }

    /**
     * @covers ::call
     */
    public function testCall()
    {
        $this->client->shouldReceive('call')
            ->andReturn(true);

        $this->assertTrue($this->connector->call('system', 'isWorking'));
    }

    /**
     * @covers            ::call
     * @expectedException Indigo\Supervisor\Exception\SupervisorException
     */
    public function testCallException()
    {
        $this->connector->call('asd', 'dsa');
    }
}
