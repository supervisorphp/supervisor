<?php

namespace Indigo\Supervisor\Test\Connector;

abstract class ConnectorTest extends \PHPUnit_Framework_TestCase
{
    protected $connector;

    public function testCredentials()
    {
        $this->assertInstanceOf(
            get_class($this->connector),
            $this->connector->setCredentials('user', '123')
        );

        $this->assertContains(
            'Basic ' . base64_encode('user:123'),
            $this->connector->getHeader()
        );

        $this->assertEquals(
            'Basic ' . base64_encode('user:123'),
            $this->connector->getHeader('Authorization')
        );
    }

    /**
     * @covers \Indigo\Supervisor\Connector\AbstractConnector::setHeader
     * @covers \Indigo\Supervisor\Connector\AbstractConnector::getHeader
     * @group  Supervisor
     */
    public function testHeaders()
    {
        $this->assertInstanceOf(
            get_class($this->connector),
            $this->connector->setHeader('X-Test', 'Test')
        );

        $this->assertInstanceOf(
            get_class($this->connector),
            $this->connector->setHeader('X-Fake-Test', 'Test', false)
        );

        $this->assertEquals('Test', $this->connector->getHeader('X-Test'));
        $this->assertEquals('Test', $this->connector->getHeader('X-Fake-Test'));

        $this->assertInstanceOf(
            get_class($this->connector),
            $this->connector->setHeader('X-Test', 'Test', false)
        );

        $this->assertEquals(array('Test', 'Test'), $this->connector->getHeader('X-Test'));
        $this->assertNull($this->connector->getHeader('X-Test-Null'));
    }

    /**
     * @group Supervisor
     */
    public function testAccessProcessResponse()
    {
        $method = new \ReflectionMethod(get_class($this->connector), 'processResponse');
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @covers            \Indigo\Supervisor\Connector\AbstractConnector::processResponse
     * @depends           testAccessProcessResponse
     * @expectedException UnexpectedValueException
     * @group             Supervisor
     */
    public function testProcessNullResponse($method)
    {
        $method->invoke($this->connector, null);
    }

    /**
     * @covers            \Indigo\Supervisor\Connector\AbstractConnector::processResponse
     * @covers            \Indigo\Supervisor\Exception\SupervisorException
     * @depends           testAccessProcessResponse
     * @expectedException \Indigo\Supervisor\Exception\SupervisorException
     * @group             Supervisor
     */
    public function testProcessFaultyResponse($method)
    {
        $response = '<?xml version="1.0" encoding="UTF-8"?>
<methodResponse>
   <fault>
      <value>
         <struct>
            <member>
               <name>faultCode</name>
               <value><int>26</int></value>
            </member>
            <member>
               <name>faultString</name>
               <value><string>No such method!</string></value>
            </member>
         </struct>
      </value>
   </fault>
</methodResponse>';

        $method->invoke($this->connector, $response);
    }

    /**
     * @covers  \Indigo\Supervisor\Connector\AbstractConnector::processResponse
     * @depends testAccessProcessResponse
     * @group   Supervisor
     */
    public function testProcessResponse($method)
    {
        $response = '<?xml version="1.0" encoding="UTF-8"?>
<methodCall>
   <methodName>fake.response</methodName>
   <params>
       <param>
            <value><int>17</int></value>
       </param>
   </params>
</methodCall>';

        $this->assertEquals(
            array(17),
            $method->invoke($this->connector, $response)
        );
    }

    /**
     * @covers \Indigo\Supervisor\Connector\AbstractConnector::isLocal
     * @group  Supervisor
     */
    public function testIsLocal()
    {
        $this->assertTrue(is_bool($this->connector->isLocal()));
    }
}
