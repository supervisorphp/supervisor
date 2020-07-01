<?php

namespace spec\Supervisor\Connector;

use fXmlRpc\ClientInterface;
use fXmlRpc\Exception\HttpException;
use PhpSpec\ObjectBehavior;
use Supervisor\Exception\Fault\UnknownMethod;
use Supervisor\Exception\Fault;
use Supervisor\Connector;
use Supervisor\Connector\XmlRpc;

class XmlRpcSpec extends ObjectBehavior
{
    function let(ClientInterface $client)
    {
        $this->beConstructedWith($client);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(XmlRpc::class);
    }

    function it_is_a_connector()
    {
        $this->shouldImplement(Connector::class);
    }

    function it_calls_a_method(ClientInterface $client)
    {
        $client->call('namespace.method', [])->willReturn('response');

        $this->call('namespace', 'method')
            ->shouldReturn('response');
    }

    function it_throws_an_exception_when_the_call_fails(ClientInterface $client)
    {
        $e = HttpException::httpError('Invalid Response', 100);

        $client->call('namespace.method', [])
            ->willThrow($e);

        $this->shouldThrow(Fault::class)
            ->duringCall('namespace', 'method');
    }

    function it_throws_a_known_exception_when_proper_fault_returned(ClientInterface $client)
    {
        $e = HttpException::httpError('UNKNOWN_METHOD', 1);

        $client->call('namespace.method', [])
            ->willThrow($e);

        $this->shouldThrow(UnknownMethod::class)
            ->duringCall('namespace', 'method');
    }
}
