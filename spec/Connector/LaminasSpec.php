<?php

namespace spec\Supervisor\Connector;

use Laminas\XmlRpc\Client;
use Laminas\XmlRpc\Client\Exception\FaultException;
use PhpSpec\ObjectBehavior;
use Supervisor\Connector;
use Supervisor\Connector\Laminas;
use Supervisor\Exception\Fault;
use Supervisor\Exception\Fault\UnknownMethod;

class LaminasSpec extends ObjectBehavior
{
    function let(Client $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Laminas::class);
    }

    function it_is_a_conncetor()
    {
        $this->shouldImplement(Connector::class);
    }

    function it_calls_a_method(Client $client)
    {
        $client->call('namespace.method', [])->willReturn('response');

        $this->call('namespace', 'method')->shouldReturn('response');
    }

    function it_throws_an_exception_when_the_call_fails(Client $client)
    {
        $e = new FaultException('Invalid response', 100);

        $client->call('namespace.method', [])->willThrow($e);

        $this->shouldThrow(Fault::class)->duringCall('namespace', 'method');
    }

    function it_throws_a_known_exception_when_proper_fault_returned(Client $client)
    {
        $e = new FaultException('UNKNOWN_METHOD', 1);

        $client->call('namespace.method', [])->willThrow($e);

        $this->shouldThrow(UnknownMethod::class)->duringCall('namespace', 'method');
    }
}
