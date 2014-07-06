<?php

namespace Indigo\Supervisor;

use Codeception\TestCase\Test;
use Indigo\Supervisor\Section\SectionInterface;

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

        $this->supervisor = new Supervisor($this->connector);
    }

    /**
     * @covers ::getConnector
     * @covers ::setConnector
     * @group  Supervisor
     */
    public function testConnector()
    {
        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Connector\\ConnectorInterface',
            $this->supervisor->getConnector()
        );

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Supervisor',
            $this->supervisor->setConnector($this->connector)
        );
    }

    /**
     * @covers ::getState
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

    public function callProvider()
    {
        $connector = \Mockery::mock('Indigo\\Supervisor\\Connector\\ConnectorInterface');
        $connector->shouldReceive('call')
            ->andReturn(true);

        $process = new Process(array('name' => 'test'), $connector);

        return array(
            array('getAPIVersion', '3.0'),
            array('getSupervisorVersion', '3.0'),
            array('getPID', 12345),
            array(
                'readLog',
                'This is a log.',
                array(0, 1),
            ),
            array('clearLog', true),
            array('shutdown', true),
            array('restart', true),
            array('getAllProcess', array()),
            array('getAllProcessInfo', array()),
            array('getProcessInfo', array(), array('test')),
            array('startAllProcesses', true),
            array('stopAllProcesses', true),
            array('startProcess', true, array('test')),
            array('stopProcess', true, array('test')),
            array('startProcess', true, array($process)),
            array('stopProcess', true, array($process)),
            array('startProcessGroup', true, array('test')),
            array('stopProcessGroup', true, array('test')),
            array('sendRemoteCommEvent', true, array('test', 'fake')),
            array('addProcessGroup', true, array('test')),
            array('removeProcessGroup', true, array('test')),
            array(
                'readProcessStdoutLog',
                'This is a log.',
                array('test', 0, 1),
            ),
            array(
                'readProcessStdoutLog',
                true,
                array($process, 0, 1),
            ),
            array(
                'readProcessStderrLog',
                'This is a log.',
                array('test', 0, 1),
            ),
            array(
                'readProcessStderrLog',
                true,
                array($process, 0, 1),
            ),
            array(
                'tailProcessStdoutLog',
                array('This is a log.', 0, false),
                array('test', 0, 1),
            ),
            array(
                'tailProcessStdoutLog',
                true,
                array($process, 0, 1),
            ),
            array(
                'tailProcessStderrLog',
                array('This is a log.', 0, false),
                array('test', 0, 1),
            ),
            array(
                'tailProcessStderrLog',
                true,
                array($process, 0, 1),
            ),
            array('clearAllProcessLogs', true),
            array(
                'clearProcessLogs',
                true,
                array('test', 0, 1),
            ),
            array(
                'clearProcessLogs',
                true,
                array($process, 0, 1),
            ),
        );
    }

    /**
     * @dataProvider callProvider
     * @group        Supervisor
     */
    public function testCallReturn($method, $value, $params = array())
    {
        $this->connector
            ->shouldReceive('call')
            ->andReturn($value);

        if (empty($params) === false) {
            $this->assertEquals(
                $value,
                call_user_func_array(
                    array($this->supervisor, $method),
                    $params
                )
            );
        } else {
            $this->assertEquals($value, $this->supervisor->{$method}());
        }
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
     * @covers ::sendProcessStdin
     * @group  Supervisor
     */
    public function testSendProcessStdin()
    {
        $this->connector->shouldReceive('call')
            ->andReturn(true);

        $process = new Process(array('name' => 'test'), $this->connector);

        $this->assertTrue($this->supervisor->sendProcessStdin('test', 'fake'));

        $this->assertTrue($this->supervisor->sendProcessStdin($process, 'fake'));
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
