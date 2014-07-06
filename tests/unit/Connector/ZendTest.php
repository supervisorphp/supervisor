<?php

namespace Indigo\Supervisor\Connector;

use Codeception\TestCase\Test;

/**
 * Tests for Zend connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\Zend
 */
class ZendTest extends Test
{
    /**
     * Zend Client
     *
     * @var Zend\XmlRpc\Client
     */
    protected $client;

    /**
     * Zend connector
     *
     * @var Indigo\Supervisor\Connector\Zend
     */
    protected $connector;

    public function _before()
    {
        $this->client = \Mockery::mock('Zend\\XmlRpc\\Client');

        $this->client->shouldReceive('getHttpClient->setAuth');

        $this->connector = new Zend($this->client);
    }

    /**
     * @covers ::__construct
     * @covers ::getClient
     * @covers ::setClient
     * @group  Supervisor
     */
    public function testInstance()
    {
        $connector = new Zend($this->client);

        $this->assertSame($this->client, $connector->getClient());
        $this->assertSame($connector, $connector->setClient($this->client));
        $this->assertSame($this->client, $connector->getClient());
    }

    /**
     * @covers ::setCredentials
     * @group  Supervisor
     */
    public function testCredentials()
    {
        $this->assertSame(
            $this->connector,
            $this->connector->setCredentials('user', '123')
        );
    }
}
