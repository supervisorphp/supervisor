<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor;

use Indigo\Supervisor\Exception\SupervisorException;
use Codeception\TestCase\Test;

/**
 * Tests for Process
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Process
 * @group              Supervisor
 * @group              Main
 */
class ProcessTest extends Test
{
    /**
     * Connector mock
     *
     * @var Connector
     */
    protected $connector;

    public function _before()
    {
        $this->connector = \Mockery::mock('Indigo\\Supervisor\\Connector');

        $this->connector->shouldReceive('isLocal')
            ->andReturn(true);
    }

    public function provider()
    {
        return [
            [
                [
                    'name' => 'test',
                    'state' => 0,
                    'pid' => 0,
                ],
            ],
            [
                [
                    'name' => 'test',
                    'state' => 0,
                    'pid' => getmypid(),
                ],
            ],
            [
                [
                    'name' => 'test',
                    'state' => 20,
                    'pid' => getmypid(),
                ],
            ],
        ];
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $info = ['name' => 'test'];

        $this->connector->shouldReceive('call')->andReturn($info);

        $process = new Process('test', $this->connector);

        $this->assertEquals('test', $process->getName());

        $process = new Process($info, $this->connector);

        $this->assertEquals('test', $process->getName());
    }

    /**
     * @dataProvider provider
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
     * @covers       ::restart
     * @covers       Indigo\Supervisor\Exception\SupervisorException
     * @dataProvider provider
     */
    public function testProcessRestartFailure($payload)
    {
        $exception = new SupervisorException;
        $this->connector->shouldReceive('call')->andThrow($exception);

        $process = new Process($payload, $this->connector);

        $this->assertFalse($process->restart());
    }
}