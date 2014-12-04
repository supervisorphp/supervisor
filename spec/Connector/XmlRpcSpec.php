<?php

namespace spec\Indigo\Supervisor\Connector;

use fXmlRpc\ClientInterface;
use fXmlRpc\Exception\ResponseException;
use PhpSpec\ObjectBehavior;

class XmlRpcSpec extends ObjectBehavior
{
    function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Connector\XmlRpc');
        $this->shouldImplement('Indigo\Supervisor\Connector');
    }

    function it_should_allow_to_send_a_call(ClientInterface $client)
    {
        $client->call('namespace.method', [])->willReturn('response');

        $this->call('namespace', 'method')->shouldReturn('response');
    }

    function it_should_throw_an_exception_when_the_call_fails(ClientInterface $client)
    {
        $e = ResponseException::fault([
            'faultString' => 'Invalid response',
            'faultCode'   => 1,
        ]);

        $client->call('namespace.method', [])->willThrow($e);

        $this->shouldThrow('Indigo\Supervisor\Exception\SupervisorException')->duringCall('namespace', 'method');
    }
}
