<?php

namespace Test\Unit;

use Codeception\TestCase\Test;

/**
 * Tests for ConnectorInterface
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
     * @var Indigo\Supervisor\Connector\ConnectorInterface
     */
    protected $connector;

    /**
     * @covers ::getClient
     * @covers ::setClient
     * @group  Supervisor
     */
    public function testInstance()
    {
        $this->assertSame($this->connector, $this->connector->setClient($this->client));
        $this->assertSame($this->client, $this->connector->getClient());
    }
}
