<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Unit;

use Indigo\Supervisor\Connector\DummyXmlrpcConnector;
use Codeception\TestCase\Test;

/**
 * Tests for Xmlrpc Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\AbstractXmlrpcConnector
 * @group              Supervisor
 * @group              Connector
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
     */
    public function testFaultyResponse()
    {
        $response = '<?xml version="1.0" encoding="UTF-8"?><methodResponse><fault><value><struct><member><name>faultCode</name><value><int>26</int></value></member><member><name>faultString</name><value><string>No such method!</string></value></member></struct></value></fault></methodResponse>';

        $this->connector->processResponse($response);
    }

    /**
     * @covers            ::processResponse
     * @expectedException UnexpectedValueException
     */
    public function testEmptyResponse()
    {
        $this->connector->processResponse('');
    }
}
