<?php

namespace Indigo\Supervisor\Connector;

abstract class SocketConnectorTest extends ConnectorTest
{
    public function testMethodCreateSocket()
    {
        $method = new \ReflectionMethod(get_class($this->connector), 'createSocket');
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @depends testMethodCreateSocket
     * @expectedException UnexpectedValueException
     */
    public function testFaultCreateSocket(\ReflectionMethod $method)
    {
        $method->invoke($this->connector, 'kaki');
    }

    /**
     * @depends testMethodCreateSocket
     */
    public function testCreateSocket(\ReflectionMethod $method)
    {
        $resource = $method->invoke($this->connector, 'google.hu', 80);

        $this->assertTrue(is_resource($resource));
    }

    /**
     * @depends testMethodCreateSocket
     */
    public function testCreatePersistentSocket(\ReflectionMethod $method)
    {
        $resource = $method->invoke($this->connector, 'google.hu', 80, null, true);

        $this->assertTrue(is_resource($resource));
    }

    public function testPersistent()
    {
        $this->assertTrue(is_bool($this->connector->isPersistent()));
    }

    public function testTimeout()
    {
        $timeout = $this->connector->setTimeout(null);

        if ($this->connector->isConnected()) {
            $this->assertTrue($timeout);
        } else {
            $this->assertFalse($timeout);
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testTimeoutFailure()
    {
        $this->connector->setTimeout('null');
    }

    public function testResource()
    {
        if ($this->connector->isConnected()) {
            $this->assertTrue(is_resource($resource = $this->connector->getResource()));

            $this->assertInstanceOf(
                'Indigo\\Supervisor\\Connector\\SocketConnector',
                $this->connector->setResource($resource)
            );
        }
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testResourceFailure()
    {
        $this->connector->setResource(null);
    }

    public function testClose()
    {
        $connector = clone $this->connector;
        unset($connector);
    }

    public function tearDown()
    {
        unset($this->connector);
    }
}
