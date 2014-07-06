<?php

namespace Indigo\Supervisor\Connector;

use Codeception\TestCase\Test;

/**
 * Tests for Xmlrpc connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\AbstractXmlrpcConnector
 */
class XmlrpcConnectorTest extends Test
{
    /**
     * Xmlrpc connector
     *
     * @var DummyXmlrpcConnector
     */
    protected $connector;

    public function _before()
    {
        $this->connector = new DummyXmlrpcConnector;
    }

    /**
     * @covers ::prepareBody
     * @group  Supervisor
     */
    public function testPrepareBody()
    {
        $body = $this->connector->prepareBody('system', 'listMethods');

        $expected = new \DOMDocument;
        $expected->loadXML('<?xml version="1.0" encoding="utf-8"?><methodCall><methodName>system.listMethods</methodName><params/></methodCall>');
        $actual = new \DOMDocument;
        $actual->loadXML($body);

        $this->assertEqualXMLStructure($expected->firstChild, $actual->firstChild);
    }

    /**
     * @covers ::processResponse
     * @group  Supervisor
     */
    public function testProcessResponse()
    {
        $response = '<?xml version="1.0" encoding="UTF-8"?><methodResponse><params><param><value><int>17</int></value></param></params></methodResponse>';

        $response = $this->connector->processResponse($response);

        $this->assertEquals(17, $response);
    }

    /**
     * @covers            ::processResponse
     * @expectedException Indigo\Supervisor\Exception\SupervisorException
     * @group             Supervisor
     */
    public function testFaultyResponse()
    {
        $response = '<?xml version="1.0" encoding="UTF-8"?><methodResponse><fault><value><struct><member><name>faultCode</name><value><int>26</int></value></member><member><name>faultString</name><value><string>No such method!</string></value></member></struct></value></fault></methodResponse>';

        $this->connector->processResponse($response);
    }

    /**
     * @covers            ::processResponse
     * @expectedException UnexpectedValueException
     * @group             Supervisor
     */
    public function testEmptyResponse()
    {
        $this->connector->processResponse('');
    }
}
