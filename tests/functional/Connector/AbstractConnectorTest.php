<?php

namespace Test\Functional;

use Codeception\TestCase\Test;

/**
 * Tests for connectors
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\ConnectorInterface
 */
abstract class AbstractConnectorTest extends Test
{
    /**
     * Connector
     *
     * @var Indigo\Supervisor\Connector\ConnectorInterface
     */
    protected $connector;

    public function _before()
    {
        if (isset($GLOBALS['username'])) {
            $this->connector->setCredentials($GLOBALS['username'], $GLOBALS['password']);
        }
    }

    /**
     * @covers ::call
     * @group  Supervisor
     */
    public function testVersion()
    {
        $this->assertGreaterThanOrEqual(3, $this->connector->call('supervisor', 'getAPIVersion'));
    }
}
