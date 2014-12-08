<?php

namespace spec\Indigo\Supervisor\XmlRpc;

use Indigo\Http\Adapter;
use Psr\Http\Message\OutgoingRequestInterface as Request;
use PhpSpec\ObjectBehavior;

class AuthenticationSpec extends ObjectBehavior
{
    function let(Adapter $adapter)
    {
        $this->beConstructedWith($adapter, 'user', '123');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\XmlRpc\Authentication');
        $this->shouldImplement('Indigo\Http\Adapter');
    }

    function it_should_allow_to_send_a_request(Adapter $adapter, Request $request)
    {
        $request->setHeader('Authorization', 'Basic dXNlcjoxMjM=')->shouldBeCalled();
        $adapter->send($request)->shouldBeCalled();

        $this->send($request);
    }
}
