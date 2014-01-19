<?php

namespace Indigo\Supervisor\EventListener;

abstract class EventListenerTest extends \PHPUnit_Framework_TestCase
{
    protected $listener;
    protected $input;
    protected $output;

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

    protected function regenerate(&$input, &$output)
    {
        $input  = @fopen('php://temp', 'r+');
        $output = @fopen('php://temp', 'w+');

        $this->listener->setInputStream($input)->setOutputStream($output);
    }
}
