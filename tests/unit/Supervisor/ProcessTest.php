<?php

namespace Indigo\Supervisor;

use Codeception\TestCase\Test;
use Indigo\Supervisor\Exception\SupervisorException;

/**
 * Tests for Process
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Process
 */
class ProcessTest extends Test
{
    protected $connector;

    public function _before()
    {
        $this->connector = \Mockery::mock(
            'Indigo\\Supervisor\\Connector\\ConnectorInterface',
            function ($mock) {
                $mock->shouldReceive('isLocal')
                    ->andReturn(true);
            }
        );
    }

    public function provider()
    {
        return array(
            array(
                array(
                    'name' => 'test',
                    'state' => 0,
                    'pid' => 0,
                )
            ),
            array(
                array(
                    'name' => 'test',
                    'state' => 0,
                    'pid' => getmypid(),
                )
            ),
            array(
                array(
                    'name' => 'test',
                    'state' => 20,
                    'pid' => getmypid(),
                )
            ),
        );
    }

    /**
     * @covers ::getConnector
     * @covers ::setConnector
     * @group  Supervisor
     */
    public function testConnector()
    {
        $process = new Process(array(), $this->connector);

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Process',
            $process->setConnector($this->connector)
        );

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Connector\\ConnectorInterface',
            $process->getConnector()
        );
    }

    /**
     * @dataProvider provider
     * @group        Supervisor
     */
    public function testProcess($payload)
    {
        $process = new Process($payload, $this->connector);

        $this->connector->shouldReceive('call')->andReturn(true);

        $this->assertEquals($payload, $process->getPayload());
        $this->assertEquals($payload['name'], $process->getName());
        $this->assertEquals($payload['name'], (string)$process);
        $this->assertEquals($payload['name'], $process['name']);
        $this->assertTrue(isset($process['name']));
        $this->assertEquals($payload['state'] == 20, $process->isRunning());

        $this->assertInstanceOf(
            get_class($this->connector),
            $process->getConnector()
        );

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Process',
            $process->setConnector($this->connector)
        );

        if ($payload['state'] == 20) {
            $this->assertGreaterThan(0, $process->getMemUsage());
        } else {
            $this->assertEquals(0, $process->getMemUsage());
        }

        foreach ($process as $key => $value) {
            $this->assertEquals($payload[$key], $value);
        }

        $process[] = 'test';
        $process['test'] = 'test';
        unset($process['test']);

        // Assert calls
        $this->assertTrue($process->start());
        $this->assertTrue($process->stop());
        $this->assertTrue($process->restart());
        $this->assertTrue($process->sendStdin('test'));
        $this->assertTrue($process->clearLogs());
        $this->assertTrue($process->readStdoutLog(0, 100));
        $this->assertTrue($process->readStderrLog(0, 100));
        $this->assertTrue($process->tailStdoutLog(0, 100));
        $this->assertTrue($process->tailStderrLog(0, 100));
    }

    /**
     * @covers            ::restart
     * @covers            Indigo\Supervisor\Exception\SupervisorException
     * @dataProvider      provider
     * @group             Supervisor
     */
    public function testProcessRestartFailure($payload)
    {
        $exception = new SupervisorException;
        $this->connector->shouldReceive('call')->andThrow($exception);

        $process = new Process($payload, $this->connector);

        $this->assertFalse($process->restart());
    }
}