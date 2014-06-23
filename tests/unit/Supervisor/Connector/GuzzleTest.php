<?php

namespace Indigo\Supervisor\Connector;

use Codeception\TestCase\Test;

/**
 * Tests for Guzzle connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\Guzzle
 */
class GuzzleTest extends Test
{
    /**
     * Guzzle Client
     *
     * @var Guzzle\Http\ClientInterface
     */
    protected $client;

    /**
     * Guzzle 3 connector
     *
     * @var Indigo\Supervisor\Connector\Guzzle
     */
    protected $connector;

    public function _before()
    {
        $this->client = \Mockery::mock('GuzzleHttp\\ClientInterface');
        $this->client->shouldReceive('getBaseUrl')
            ->andReturn($GLOBALS['host']);

        $this->connector = new Guzzle($this->client);
    }

    /**
     * @covers ::__construct
     * @covers ::getClient
     * @covers ::setClient
     * @group  Supervisor
     */
    public function testInstance()
    {
        $connector = new Guzzle($this->client);

        $this->assertSame($this->client, $connector->getClient());
        $this->assertSame($connector, $connector->setClient($this->client));
        $this->assertSame($this->client, $connector->getClient());
    }

    /**
     * @covers ::prepareBody
     * @group  Supervisor
     */
    public function testPrepareBody()
    {
        $this->assertInstanceOf('GuzzleHttp\\Stream\\StreamInterface', $this->connector->prepareBody('system', 'listMethods', array()));
    }

    /**
     * @covers ::prepareRequest
     * @group  Supervisor
     */
    public function testPrepareRequest()
    {
        $request = \Mockery::mock('GuzzleHttp\\Message\\RequestInterface');

        $request->shouldReceive('setMethod')
            ->andReturn($request);

        $request->shouldReceive('setHeaders')
            ->andReturn($request);

        $request->shouldReceive('setPath')
            ->andReturn($request);

        $request->shouldReceive('setBody')
            ->andReturn($request);

        $stream = \Mockery::mock('GuzzleHttp\\Stream\\StreamInterface');
        $stream->shouldReceive('getSize')
            ->andReturn(1234);

        $this->connector->setCredentials($GLOBALS['username'], $GLOBALS['password']);

        $this->connector->prepareRequest($request, $stream);
    }
}
