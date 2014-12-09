<?php

namespace spec\Indigo\Supervisor\XmlRpc;

use Indigo\Http\Adapter;
use Psr\Http\Message\IncomingResponseInterface as Response;
use Psr\Http\Message\StreamableInterface as Stream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function let(Adapter $adapter)
    {
        $this->beConstructedWith('http://127.0.0.1:9001/RPC2', $adapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Supervisor\XmlRpc\Client');
        $this->shouldImplement('fXmlRpc\ClientInterface');
    }

    function it_should_have_an_uri()
    {
        $this->getUri()->shouldReturn('http://127.0.0.1:9001/RPC2');
    }

    function it_should_allow_to_set_an_uri()
    {
        $this->setUri('http://localhost:9001/RPC2');

        $this->getUri()->shouldReturn('http://localhost:9001/RPC2');
    }

    function it_should_allow_to_prepend_params()
    {
        $this->prependParams(['preParam1']);

        $this->getPrependParams()->shouldReturn(['preParam1']);
    }

    function it_should_allow_to_append_params()
    {
        $this->appendParams(['appParam1']);

        $this->getAppendParams()->shouldReturn(['appParam1']);
    }

    function it_should_allow_to_call(Adapter $adapter, Response $response, Stream $body)
    {
        $body->getContents()->willReturn('<?xml version=\'1.0\'?><methodResponse><params><param><value><string>supervisor</string></value></param></params></methodResponse>');
        $response->getBody()->willReturn($body);
        $adapter->send(Argument::type('Psr\Http\Message\OutgoingRequestInterface'))->willReturn($response);

        $this->call('supervisor.getIdentification')->shouldReturn('supervisor');
    }

    function it_should_throw_an_exception_when_response_is_fault(Adapter $adapter, Response $response, Stream $body)
    {
        $body->getContents()->willReturn('<?xml version=\'1.0\'?><methodResponse><fault><value><struct><member><name>faultCode</name><value><int>1</int></value></member><member><name>faultString</name><value><string>UNKNOWN_METHOD</string></value></member></struct></value></fault></methodResponse>');
        $response->getBody()->willReturn($body);
        $adapter->send(Argument::type('Psr\Http\Message\OutgoingRequestInterface'))->willReturn($response);

        $this->shouldThrow('fXmlRpc\Exception\ResponseException')->duringCall('supervisor.getInvalidCall');
    }

    function it_should_allow_to_start_a_multicall()
    {
        $multicall = $this->multicall();

        $multicall->shouldHaveType('fXmlRpc\Multicall');
        $multicall->getClient()->shouldReturn($this);
    }
}
