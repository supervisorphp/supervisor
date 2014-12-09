<?php

namespace spec\Indigo\Supervisor\Event\Listener;

use GuzzleHttp\Stream\StreamInterface;
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
}
