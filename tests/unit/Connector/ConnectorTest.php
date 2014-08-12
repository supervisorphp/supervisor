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

use Indigo\Supervisor\Connector\DummyConnector;
use Codeception\TestCase\Test;

/**
 * Tests for Abstract Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\AbstractConnector
 * @group              Supervisor
 * @group              Connector
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
     */
    public function testCredentials()
    {
        $this->assertSame($this->connector, $this->connector->setCredentials('user', 123));
    }

    /**
     * @covers ::isLocal
     */
    public function testIsLocal()
    {
        $this->assertTrue($this->connector->isLocal());
    }
}
