<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Supervisor\Event;

use Indigo\Supervisor\Event;
use Codeception\TestCase\Test;

/**
 * Tests for Processors
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Event\Processor
 * @group              Supervisor
 * @group              Event
 */
abstract class AbstractProcessorTest extends Test
{
    /**
     * Processor object
     *
     * @var Processor
     */
    protected $processor;

    /**
     * Header values
     *
     * @var []
     */
    protected $header = [
        'ver'        => '3.0',
        'server'     => 'supervisor',
        'serial'     => '21',
        'pool'       => 'listener',
        'poolserial' => '10',
        'eventname'  => 'PROCESS_COMMUNICATION_STDOUT',
        'len'        => null,
    ];

    /**
     * Payload values
     *
     * @var []
     */
    protected $payload = [
        'process_name' => 'foo',
        'group_name'   => 'bar',
        'pid'          => '123',
    ];

    /**
     * Body value
     *
     * @var string
     */
    protected $body = 'This is the data that was sent between the tags';

    /**
     * Plain event string
     *
     * @var string
     */
    protected $event;

    public function _before()
    {
        $event = $this->invertData($this->payload);
        $event .= "\n";
        $event .= $this->body;

        $this->header['len'] = strlen($event);
        $this->event = $this->invertData($this->header) . "\n" . $event;
    }

    /**
     * Inverts parsed data
     *
     * @param [] $data
     *
     * @return string
     */
    protected function invertData($data)
    {
        $output = [];

        foreach ($data as $key => $value) {
            $output[] = $key.':'.$value;
        }

        return implode(' ', $output);
    }

    /**
     * Returns an emitter mock making sure that the event stops processor
     *
     * @return League\Event\EmitterInterface
     */
    protected function getEmitterMock()
    {
        $emitter = \Mockery::mock('League\\Event\\EmitterInterface');

        $emitter->shouldReceive('emit')
            ->andReturnUsing(function(Event $event) {
                $event->stopProcessor();
            });

        return $emitter;
    }

    /**
     * Processor write proxy (for input stream)
     *
     * Stream should be seeked to the start point of the write
     *
     * @param mixed $data
     */
    abstract protected function write($data);

    /**
     * Processor read line proxy (for output stream)
     *
     * @param integer $maxLength
     *
     * @return string
     */
    abstract protected function readLine();

    /**
     * Proxy for rewinding output stream
     */
    abstract protected function rewindOutput();

    public function testRun()
    {
        $this->write($this->event);

        $this->processor->run();

        $this->rewindOutput();
        $this->assertEquals('READY', $this->readLine());
        $this->assertEquals('RESULT 4', $this->readLine());
        $this->assertEquals('FAIL', $this->readLine());
    }
}