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

use Indigo\Supervisor\Connector\GuzzleConnector;
use Codeception\TestCase\Test;

/**
 * Tests for Guzzle connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\GuzzleConnector
 * @group              Supervisor
 * @group              Connector
 */
class GuzzleConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $this->client = \Mockery::mock('GuzzleHttp\\ClientInterface');
        $this->client->shouldReceive('getBaseUrl')
            ->andReturn($GLOBALS['host']);

        $this->connector = new GuzzleConnector($this->client);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $connector = new GuzzleConnector($this->client);

        $this->assertSame($this->client, $connector->getClient());
    }

    /**
     * @covers ::prepareBody
     */
    public function testPrepareBody()
    {
        $this->assertInstanceOf('GuzzleHttp\\Stream\\StreamInterface', $this->connector->prepareBody('system', 'listMethods', array()));
    }

    /**
     * @covers ::prepareRequest
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
