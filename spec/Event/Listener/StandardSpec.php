<?php

namespace spec\Indigo\Supervisor\Event\Listener;

use PhpSpec\ObjectBehavior;

class StandardSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Event\Listener\Standard');
        $this->shouldHaveType('Indigo\Supervisor\Event\Listener');
    }

    function it_should_have_an_input_stream()
    {
        $this->getInputStream()->shouldReturn(STDIN);
    }

    function it_should_have_an_output_stream()
    {
        $this->getOutputStream()->shouldReturn(STDOUT);
    }
}
