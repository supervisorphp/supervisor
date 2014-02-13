<?php

namespace Indigo\Supervisor\Test\Event;

abstract class AbstractEventTest extends \PHPUnit_Framework_TestCase
{
    protected $event;

    protected $header = array(
        'ver'        => '3.0',
        'server'     => 'supervisor',
        'serial'     => '21',
        'pool'       => 'listener',
        'poolserial' => '10',
        'eventname'  => 'PROCESS_COMMUNICATION_STDOUT',
        'len'        => '54',
    );

    protected $payload = array(
        'process_name' => 'foo',
        'group_name'   => 'bar',
        'pid'          => '123',
    );

    protected $body = 'This is the data that was sent between the tags';

    public function testGetSet()
    {
        $this->assertNull($this->event->getHeader('fake'));
        $this->assertEquals($this->header['ver'], $this->event->getHeader('ver'));
        $this->assertEquals('fake', $this->event->getHeader('fake', 'fake'));
        $this->assertInstanceOf(
            get_class($this->event),
            $this->event->setHeader($this->header)
        );
        $this->assertEquals($this->header, $this->event->getHeader());


        $this->assertNull($this->event->getPayload('fake'));
        $this->assertEquals($this->payload['pid'], $this->event->getPayload('pid'));
        $this->assertEquals('fake', $this->event->getPayload('fake', 'fake'));
        $this->assertInstanceOf(
            get_class($this->event),
            $this->event->setPayload($this->payload)
        );
        $this->assertEquals($this->payload, $this->event->getPayload());


        $this->assertInstanceOf(
            get_class($this->event),
            $this->event->setBody($this->body)
        );
        $this->assertEquals($this->body, $this->event->getBody());
    }

    public function testInstance()
    {
        $this->assertInstanceOf(
            'Indigo\\Supervisor\\Event\\EventInterface',
            $this->event
        );
    }
}
