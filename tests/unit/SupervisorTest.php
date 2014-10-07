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

use Indigo\Supervisor\Section\SectionInterface;
use Indigo\Supervisor\Supervisor;
use Indigo\Supervisor\Process;
use Codeception\TestCase\Test;

/**
 * Tests for Supervisor
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Supervisor
 * @group              Supervisor
 * @group              Main
 */
class SupervisorTest extends Test
{
    protected $connector;
    protected $supervisor;

    public function _before()
    {
        $this->connector = \Mockery::mock('Indigo\\Supervisor\\Connector');

        $this->connector->shouldReceive('isLocal')
            ->andReturn(true);

        $this->connector->shouldReceive('call')
            ->andReturn(true)
            ->byDefault();

        $this->supervisor = new Supervisor($this->connector);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $supervisor = new Supervisor($this->connector);
    }

    /**
     * @covers ::isLocal
     */
    public function testIsLocal()
    {
        $this->assertTrue($this->supervisor->isLocal());
    }

    /**
     * @covers ::isState
     * @covers ::isRunning
     */
    public function testState()
    {
        $this->connector->shouldReceive('call')
            ->andReturn(array(
                'statecode' => 1,
                'statename' => 'RUNNING'
            ));

        $this->assertEquals(
            array(
                'statecode' => 1,
                'statename' => 'RUNNING'
            ),
            $this->supervisor->getState()
        );

        $this->assertTrue($this->supervisor->isState());
        $this->assertTrue($this->supervisor->isRunning());
    }

    /**
     * @covers ::call
     * @covers ::__call
     */
    public function testCall()
    {
        $process = new Process(array('name' => 'test'), $this->connector);

        $this->assertTrue($this->supervisor->startProcess($process));
        $this->assertTrue($this->supervisor->startProcess('test'));
    }

    /**
     * @covers ::getProcess
     * @covers Indigo\Supervisor\Process
     */
    public function testGetProcess()
    {
        $this->connector
            ->shouldReceive('call')
            ->andReturn(array('name' => 'test'));

        $process = new Process(array('name' => 'test'), $this->connector);

        $this->assertEquals(
            $process,
            $this->supervisor->getProcess('test')
        );
    }
}
