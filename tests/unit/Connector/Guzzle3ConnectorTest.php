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

use Indigo\Supervisor\Connector\Guzzle3Connector;

/**
 * Tests for Guzzle 3 Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\Guzzle3Connector
 * @group              Supervisor
 * @group              Connector
 */
class Guzzle3ConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $this->client = \Mockery::mock('Guzzle\\Http\\ClientInterface');

        $this->client->shouldReceive('getBaseUrl')
            ->andReturn($GLOBALS['host']);

        $this->connector = new Guzzle3Connector($this->client);
    }

    /**
     * @covers ::__construct
     */
    public function testConstruct()
    {
        $connector = new Guzzle3Connector($this->client);

        $this->assertSame($this->client, $connector->getClient());
    }

    /**
     * @covers ::prepareRequest
     */
    public function testPrepareRequest()
    {
        $request = \Mockery::mock('Guzzle\\Http\\Message\\RequestInterface');

        $request->shouldReceive('setAuth')
            ->andReturn($request);

        $request->shouldReceive('addHeaders')
            ->andReturn($request);

        $request->shouldReceive('setPath')
            ->andReturn($request);

        $request->shouldReceive('setBody')
            ->andReturn($request);


        $this->connector->setCredentials($GLOBALS['username'], $GLOBALS['password']);

        $this->connector->prepareRequest($request, 'test');
    }
}
