<?php

namespace spec\Supervisor;

use PhpSpec\ObjectBehavior;
use Supervisor\ServiceStates;
use Supervisor\StateInfo;
use Supervisor\StateInfoInterface;

class StateInfoSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(ServiceStates::Running, 'RUNNING');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(StateInfo::class);
    }

    function it_implements_state_info_interface()
    {
        $this->shouldImplement(StateInfoInterface::class);
    }

    function it_returns_state_code()
    {
        $this->getStateCode()->shouldReturn(ServiceStates::Running);
    }

    function it_returns_state_name()
    {
        $this->getStateName()->shouldReturn('RUNNING');
    }

    function it_can_be_built_from_get_state_response()
    {
        $this->beConstructedThrough('fromGetState', [['statecode' => 1, 'statename' => 'RUNNING']]);

        $this->getStateCode()->shouldReturn(ServiceStates::Running);
        $this->getStateName()->shouldReturn('RUNNING');
    }
}
