<?php

namespace Indigo\Supervisor\EventListener;

use Indigo\Supervisor\Process;

class TestMemmonEventListener extends MemmonEventListener
{
    protected function processResult($result)
    {
        parent::processResult($result);
        return false;
    }
}

class MemmonEventListenerTest extends EventListenerTest
{
    public function setUp()
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
                ), $connector),
                function ($mock) {
                    $mock->shouldReceive('getMemUsage')
                        ->andReturn(1024);

                    $mock->shouldReceive('isRunning')
                        ->andReturn(true);

                    $mock->shouldReceive('restart')
                        ->andReturn(false);
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

            $mock->shouldReceive('getAllProcess')
                ->andReturn(array(
                    $process,
                    $process2,
                    $process3,
                    $process4,
                ));
        });

        $this->listener = new TestMemmonEventListener($supervisor, array(), array(), 1024, 60, 'memmon');
    }

    public function testInstance()
    {
        $supervisor = \Mockery::mock('Indigo\\Supervisor\\Supervisor');
        $listener = new MemmonEventListener($supervisor, array(), array(), 1024, 60, 'memmon');

        $this->assertInstanceOf(
            'Indigo\\Supervisor\\EventListener\\MemmonEventListener',
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

    public function tearDown()
    {
        \Mockery::close();
    }
}
