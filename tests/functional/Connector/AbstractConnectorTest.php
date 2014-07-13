<?php

namespace Test\Functional;

use Codeception\TestCase\Test;
use Indigo\Supervisor\Supervisor;

/**
 * Tests for connectors
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
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
     * @covers Indigo\Supervisor\Supervisor
     * @group  Supervisor
     */
    public function testSupervisor()
    {
        $supervisor = new Supervisor($this->connector);

        $this->assertGreaterThanOrEqual(3, $supervisor->getAPIVersion());
    }

    /**
     * @covers            Indigo\Supervisor\Supervisor
     * @expectedException Indigo\Supervisor\Exception\SupervisorException
     * @expectedMessage   INCORRECT_PARAMETERS
     * @group             Supervisor
     */
    public function testSupervisorFail()
    {
        $supervisor = new Supervisor($this->connector);

        $supervisor->getAPIVersion('incorrect_param');
    }

    /**
     * @covers ::call
     * @group  Supervisor
     */
    public function testVersion()
    {
        $this->assertGreaterThanOrEqual(3, $this->connector->call('supervisor', 'getAPIVersion'));
    }

    /**
     * @covers                   ::call
     * @expectedException        Indigo\Supervisor\Exception\SupervisorException
     * @expectedExceptionMessage UNKNOWN_METHOD
     * @group                    Supervisor
     */
    public function testFaultyCall()
    {
        $this->connector->call('non', 'existent');
    }
}
