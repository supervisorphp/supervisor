<?php

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
 */
class SupervisorTest extends Test
{
    protected $connector;
    protected $supervisor;

    public function _before()
    {
        $this->connector = \Mockery::mock('Indigo\\Supervisor\\Connector\\ConnectorInterface');

        $this->connector->shouldReceive('isLocal')
            ->andReturn(true);

        $this->connector->shouldReceive('call')
            ->andReturn(true)
            ->byDefault();

        $this->supervisor = new Supervisor($this->connector);
    }

    /**
     * @covers ::getConnector
     * @covers ::setConnector
     * @group  Supervisor
     */
    public function testConnector()
    {
        $this->assertSame(
            $this->supervisor,
            $this->supervisor->setConnector($this->connector)
        );

        $this->assertSame(
            $this->connector,
            $this->supervisor->getConnector()
        );
    }

    /**
     * @covers ::isState
     * @covers ::isRunning
     * @group  Supervisor
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
     * @covers ::__call
     * @group  Supervisor
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
     * @group  Supervisor
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

    /**
     * @covers ::isLocal
     * @group  Supervisor
     */
    public function testIsLocal()
    {
        $this->assertTrue($this->supervisor->isLocal());
    }
}
