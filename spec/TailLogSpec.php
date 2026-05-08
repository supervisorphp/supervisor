<?php

namespace spec\Supervisor;

use PhpSpec\ObjectBehavior;
use Supervisor\TailLog;
use Supervisor\TailLogInterface;

class TailLogSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('log content', 11, false);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TailLog::class);
    }

    function it_implements_tail_log_interface()
    {
        $this->shouldImplement(TailLogInterface::class);
    }

    function it_returns_bytes()
    {
        $this->getBytes()->shouldReturn('log content');
    }

    function it_returns_offset()
    {
        $this->getOffset()->shouldReturn(11);
    }

    function it_returns_overflow_flag()
    {
        $this->isOverflow()->shouldReturn(false);
    }

    function it_can_be_built_from_tail_log_response()
    {
        $this->beConstructedThrough('fromTailLog', [['log content', 11, false]]);

        $this->getBytes()->shouldReturn('log content');
        $this->getOffset()->shouldReturn(11);
        $this->isOverflow()->shouldReturn(false);
    }

    function it_casts_overflow_to_bool_when_built_from_response()
    {
        $this->beConstructedThrough('fromTailLog', [['data', 5, 1]]);

        $this->isOverflow()->shouldReturn(true);
    }
}
