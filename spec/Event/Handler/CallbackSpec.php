<?php

namespace spec\Indigo\Supervisor\Event\Handler;

use Indigo\Supervisor\Event\Notification;
use Indigo\Supervisor\Exception\EventHandlingFailed;
use PhpSpec\ObjectBehavior;

class CallbackSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(function() {});
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Event\Handler\Callback');
        $this->shouldImplement('Indigo\Supervisor\Event\Handler');
    }

    function it_should_allow_to_handle_a_notification(Notification $notification)
    {
        $this->handle($notification);
    }

    function it_should_throw_an_exception_when_handling_failed(Notification $notification)
    {
        $this->beConstructedWith(function() { throw new EventHandlingFailed; });
        $this->shouldThrow('Indigo\Supervisor\Exception\EventHandlingFailed')->duringHandle($notification);
    }
}
