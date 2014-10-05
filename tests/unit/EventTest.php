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

use Indigo\Supervisor\Event\Processor;
use Codeception\TestCase\Test;

/**
 * Tests for Event
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Event
 * @group              Supervisor
 * @group              Event
 */
class EventTest extends Test
{
    /**
     * Event object
     *
     * @var Event
     */
    protected $event;

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
        'len'        => '54',
    ];

    protected $payload = [
        'process_name' => 'foo',
        'group_name'   => 'bar',
        'pid'          => '123',
    ];

    protected $body = 'This is the data that was sent between the tags';

    public function _before()
    {
        $this->event = new Event($this->header, $this->payload, $this->body);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $event = new Event($this->header, $this->payload, $this->body);

        $this->assertEquals($this->header, $event->getHeader());
        $this->assertEquals($this->payload, $event->getPayload());
        $this->assertEquals($this->body, $event->getBody());
        $this->assertNull($event->getResult());
    }

    /**
     * @covers ::getHeader
     * @covers ::setHeader
     */
    public function testHeader()
    {
        $this->assertNull($this->event->getHeader('fake'));
        $this->assertEquals($this->header['ver'], $this->event->getHeader('ver'));
        $this->assertEquals('fake', $this->event->getHeader('fake', 'fake'));
        $this->assertSame($this->event, $this->event->setHeader($this->header));
        $this->assertEquals($this->header, $this->event->getHeader());
    }

    /**
     * @covers ::getPayload
     * @covers ::setPayload
     */
    public function testPayload()
    {
        $this->assertNull($this->event->getPayload('fake'));
        $this->assertEquals($this->payload['pid'], $this->event->getPayload('pid'));
        $this->assertEquals('fake', $this->event->getPayload('fake', 'fake'));
        $this->assertSame($this->event, $this->event->setPayload($this->payload));
        $this->assertEquals($this->payload, $this->event->getPayload());
    }

    /**
     * @covers ::getBody
     * @covers ::setBody
     */
    public function testBody()
    {
        $this->assertSame($this->event, $this->event->setBody($this->body));
        $this->assertEquals($this->body, $this->event->getBody());
    }

    /**
     * @covers ::getResult
     * @covers ::setResult
     */
    public function testResult()
    {
        $this->assertNull($this->event->getResult());
        $this->assertSame($this->event, $this->event->setResult(Processor::OK));
        $this->assertEquals(Processor::OK, $this->event->getResult());
    }

    /**
     * @covers ::stopProcessor
     * @covers ::shouldProcessorStop
     */
    public function testStopProcessor()
    {
        $this->assertFalse($this->event->shouldProcessorStop());
        $this->assertSame($this->event, $this->event->stopProcessor());
        $this->assertTrue($this->event->shouldProcessorStop());
    }
}
