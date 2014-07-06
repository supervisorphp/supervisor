<?php

namespace Indigo\Supervisor\Connector;

use Codeception\TestCase\Test;

/**
 * Tests for connectors
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\ConnectorInterface
 */
abstract class AbstractConnectorFunctionalTest extends Test
{
    /**
     * Connector
     *
     * @var Indigo\Supervisor\Connector\ConnectorInterface
     */
    protected $connector;

    /**
     * @covers ::call
     * @group  Supervisor
     */
    public function testVersion()
    {
        $this->assertGreaterThanOrEqual(3, $this->connector->call('supervisor', 'getAPIVersion'));
    }
}
