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
     * @var Indigo\Supervisor\Connector\ConnectorInterface
     */
    protected $connector;

    /**
     * @covers ::getClient
     * @covers ::setClient
     */
    public function testInstance()
    {
        $this->assertSame($this->connector, $this->connector->setClient($this->client));
        $this->assertSame($this->client, $this->connector->getClient());
    }
}
