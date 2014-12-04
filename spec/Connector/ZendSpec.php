<?php

namespace spec\Indigo\Supervisor\Connector;

use Zend\XmlRpc\Client;
use Zend\XmlRpc\Client\Exception\FaultException;
use PhpSpec\ObjectBehavior;

class ZendSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\Connector\Zend');
        $this->shouldImplement('Indigo\Supervisor\Connector');
    }

    function it_should_allow_to_send_a_call(Client $client)
    {
        $client->call('namespace.method', [])->willReturn('response');

        $this->call('namespace', 'method')->shouldReturn('response');
    }

    function it_should_throw_an_exception_when_the_call_fails(Client $client)
    {
        $e = new FaultException('Invalid response', 1);

        $client->call('namespace.method', [])->willThrow($e);

        $this->shouldThrow('Indigo\Supervisor\Exception\SupervisorException')->duringCall('namespace', 'method');
    }
}
