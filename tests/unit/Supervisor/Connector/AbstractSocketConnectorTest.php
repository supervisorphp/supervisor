<?php

namespace Indigo\Supervisor\Connector;

use Codeception\TestCase\Test;

abstract class AbstractSocketConnectorTest extends Test
{
    public function _after()
    {
        unset($this->connector);
    }

    /**
     * @group Supervisor
     */
    public function testMethodCreateSocket()
    {
        $method = new \ReflectionMethod(get_class($this->connector), 'createSocket');
        $method->setAccessible(true);

        return $method;
    }

    /**
     * @covers            ::createSocket
     * @depends           testMethodCreateSocket
     * @expectedException RuntimeException
     * @group             Supervisor
     */
    public function testFaultCreateSocket(\ReflectionMethod $method)
    {
        $method->invoke($this->connector, 'fake');
    }

    /**
     * @covers  ::createSocket
     * @depends testMethodCreateSocket
     * @group   Supervisor
     */
    public function testCreateSocket(\ReflectionMethod $method)
    {
        $resource = $method->invoke($this->connector, 'google.hu', 80);

        $this->assertTrue(is_resource($resource));
    }

    /**
     * @covers  ::createSocket
     * @depends testMethodCreateSocket
     * @group   Supervisor
     */
    public function testCreatePersistentSocket(\ReflectionMethod $method)
    {
        $resource = $method->invoke($this->connector, 'google.hu', 80, null, true);

        $this->assertTrue(is_resource($resource));
    }

    /**
     * @covers ::isPersistent
     * @group  Supervisor
     */
    public function testPersistent()
    {
        $this->assertTrue(is_bool($this->connector->isPersistent()));
    }

    /**
     * @covers ::setTimeout
     * @covers ::validateTimeout
     * @group  Supervisor
     */
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
     * @covers            ::setTimeout
     * @covers            ::validateTimeout
     * @expectedException InvalidArgumentException
     * @group             Supervisor
     */
    public function testTimeoutFailure()
    {
        $this->connector->setTimeout('null');
    }

    /**
     * @covers ::getResource
     * @covers ::setResource
     * @group  Supervisor
     */
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
     * @covers            ::setResource
     * @expectedException InvalidArgumentException
     * @group             Supervisor
     */
    public function testResourceFailure()
    {
        $this->connector->setResource(null);
    }

    /**
     * @covers ::prepareRequest
     * @group  Supervisor
     */
    public function testPrepareRequest()
    {
        $method = new \ReflectionMethod(get_class($this->connector), 'prepareRequest');
        $method->setAccessible(true);

        $this->assertInstanceOf(
            'Buzz\\Message\\RequestInterface',
            $method->invoke($this->connector, 'namespace', 'method', array())
        );
    }

    /**
     * @covers ::prepareClient
     * @group  Supervisor
     */
    public function testPrepareClient()
    {
        $method = new \ReflectionMethod(get_class($this->connector), 'prepareClient');
        $method->setAccessible(true);

        $this->assertInstanceOf(
            'Buzz\\Client\\ClientInterface',
            $method->invoke($this->connector)
        );
    }

    /**
     * @covers ::close
     * @covers ::isConnected
     * @covers ::setTimeout
     * @group  Supervisor
     */
    public function testClose()
    {
        $connector = clone $this->connector;
        $connector->close();

        $this->assertFalse($connector->isConnected());

        $timeout = $this->connector->setTimeout(null);
        $this->assertFalse($timeout);
    }

    /**
     * @covers ::__destruct
     * @covers ::close
     * @group  Supervisor
     */
    public function testDestruct()
    {
        $connector = clone $this->connector;
        unset($connector);
    }
}
