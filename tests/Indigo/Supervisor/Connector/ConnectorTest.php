<?php

namespace Indigo\Supervisor\Connector;


abstract class ConnectorTest extends \PHPUnit_Framework_TestCase
{
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
    }

    public function testResource()
    {
        $connector = clone $this->connector;
        $this->assertInstanceOf(
            get_class($connector),
            $connector->setResource(null)
        );

        $this->assertNull($connector->getResource());
    }

    public function testProcess()
    {
        # code...
    }
}