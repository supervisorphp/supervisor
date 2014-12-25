<?php

namespace spec\Indigo\Supervisor\Event\Listener;

use GuzzleHttp\Stream\StreamInterface;
use Indigo\Supervisor\Stub\Handler;
use PhpSpec\ObjectBehavior;

class StreamSpec extends ObjectBehavior
{
    function let(StreamInterface $inputStream, StreamInterface $outputStream)
    {
        $this->beConstructedWith($inputStream, $outputStream);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Event\Listener\Stream');
        $this->shouldImplement('Indigo\Supervisor\Event\Listener');
    }

    function it_should_have_an_input_stream(StreamInterface $inputStream)
    {
        $this->getInputStream()->shouldReturn($inputStream);
    }

    function it_should_have_an_output_stream(StreamInterface $outputStream)
    {
        $this->getOutputStream()->shouldReturn($outputStream);
    }

    function it_should_allow_to_listen(StreamInterface $inputStream, StreamInterface $outputStream)
    {
        $handler = new Handler;

        $header = '';

        $inputStream->eof()->willReturn(false);
        $inputStream->read(1)->will(function($args) use (&$header) {
            if (empty($header)) {
                $header = str_split("ver:3.0 server:supervisor serial:21 pool:listener poolserial:10 eventname:PROCESS_COMMUNICATION_STDOUT len:86\n");
                return "\n";
            }

            return array_shift($header);
        });
        $inputStream->read(86)->will(function($args) use (&$header) {
            $header = str_split("ver:3.0 server:supervisor serial:21 pool:listener poolserial:10 eventname:PROCESS_COMMUNICATION_STDOUT len:86\n");
            return "processname:foo groupname:bar pid:123\nThis is the data that was sent between the tags";
        });

        $outputStream->write("READY\n")->shouldBeCalledTimes(4);
        $outputStream->write("RESULT 2\nOK")->shouldBeCalled();
        $outputStream->write("RESULT 4\nFAIL")->shouldBeCalled();

        $this->listen($handler);
    }
}
