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

use Indigo\Supervisor\Process;
use Indigo\Supervisor\Event\MemmonListener;
use Indigo\Supervisor\Event\DummyMemmonListener;

/**
 * Tests for Memmon Listener
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Event\MemmonListener
 * @group              Supervisor
 * @group              Listener
 */
class MemmonListenerTest extends AbstractListenerTest
{
    public function _before()
    {
        $supervisor = \Mockery::mock('Indigo\\Supervisor\\Supervisor', function ($mock) {
            $connector = \Mockery::mock('Indigo\\Supervisor\\Connector\\ConnectorInterface');

            $process = \Mockery::mock(new Process(array(
                    'name' => 'test',
                    'group' => 'test',
                    'now' => 1000,
                    'start' => 1,
                ), $connector),
                function ($mock) {
                    $mock->shouldReceive('getMemUsage')
                        ->andReturn(1024);

                    $mock->shouldReceive('isRunning')
                        ->andReturn(true);

                    $mock->shouldReceive('restart')
                        ->andReturn(true);
                }
            );

            $process2 = \Mockery::mock(new Process(array(
                    'name' => 'test',
                    'group' => 'test',
                    'now' => 1000,
                    'start' => 0,
                    'pid' => getmypid(),
                ), $connector),
                function ($mock) {
                    $mock->shouldReceive('getMemUsage')
                        ->andReturn(1025);

                    $mock->shouldReceive('isRunning')
                        ->andReturn(true);

                    $mock->shouldReceive('restart')
                        ->andThrow(new \Exception);
                }
            );

            $process3 = \Mockery::mock(new Process(array(
                    'now' => 0,
                    'start' => 0,
                ), $connector),
                function ($mock) {
                    $mock->shouldReceive('isRunning')
                        ->andReturn(true);
                }
            );

            $process4 = \Mockery::mock(
                new Process(array(), $connector),
                function ($mock) {
                    $mock->shouldReceive('isRunning')
                        ->andReturn(false);
                }
            );

            $mock->shouldReceive('getAllProcesses')
                ->andReturn(array(
                    $process,
                    $process2,
                    $process3,
                    $process4,
                ));
        });

        $this->listener = new DummyMemmonListener($supervisor, array(), array(), 1024, 60, 'memmon');
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $supervisor = \Mockery::mock('Indigo\\Supervisor\\Supervisor');
        $listener = new MemmonListener($supervisor, array(), array(), 1024, 60, 'memmon');

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Event\\MemmonListener',
            $listener
        );
    }

    public function testBasic()
    {
        $this->regenerate($input, $output);

        fwrite($input, "ver:3.0 server:supervisor serial:21 pool:listener poolserial:10 eventname:PROCESS_COMMUNICATION_STDOUT len:87\nprocess_name:foo group_name:bar pid:123\nThis is the data that was sent between the tags");
        rewind($input);

        $this->listener->listen();

        rewind($output);
        $this->assertEquals("READY\n", fgets($output));
        $this->assertEquals("RESULT 2\n", fgets($output));
        $this->assertEquals('OK', fgets($output));
    }

    public function testAdvanced()
    {
        $this->regenerate($input, $output);

        fwrite($input, "ver:3.0 server:supervisor serial:21 pool:listener poolserial:10 eventname:TICK_5 len:54\nprocess_name:foo group_name:bar pid:123\nThis is the data that was sent between the tags");
        rewind($input);

        $this->listener->listen();

        rewind($output);
        $this->assertEquals("READY\n", fgets($output));
        $this->assertEquals("RESULT 2\n", fgets($output));
        $this->assertEquals('OK', fgets($output));
    }
}
