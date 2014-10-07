<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Functional;

use Indigo\Supervisor\Supervisor;
use Codeception\TestCase\Test;

/**
 * Tests for Connectors
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
abstract class AbstractConnectorTest extends Test
{
    /**
     * Connector
     *
     * @var Indigo\Supervisor\Connector
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
     */
    public function testSupervisorFail()
    {
        $supervisor = new Supervisor($this->connector);

        $supervisor->getAPIVersion('incorrect_param');
    }

    /**
     * @covers ::call
     */
    public function testVersion()
    {
        $this->assertGreaterThanOrEqual(3, $this->connector->call('supervisor', 'getAPIVersion'));
    }

    /**
     * @covers                   ::call
     * @expectedException        Indigo\Supervisor\Exception\SupervisorException
     * @expectedExceptionMessage UNKNOWN_METHOD
     */
    public function testFaultyCall()
    {
        $this->connector->call('non', 'existent');
    }
}
