<?php

namespace Test\Unit;

use Codeception\TestCase\Test;
use Indigo\Supervisor\Connector\Guzzle3;

/**
 * Tests for Guzzle 3 connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\Guzzle3
 */
class Guzzle3Test extends AbstractConnectorTest
{
    public function _before()
    {
        $this->client = \Mockery::mock('Guzzle\\Http\\ClientInterface');

        $this->client->shouldReceive('getBaseUrl')
            ->andReturn($GLOBALS['host']);

        $this->connector = new Guzzle3($this->client);
    }

    /**
     * @covers ::__construct
     * @group  Supervisor
     */
    public function testConstruct()
    {
        $connector = new Guzzle3($this->client);

        $this->assertSame($this->client, $connector->getClient());
    }

    /**
     * @covers ::prepareRequest
     * @group  Supervisor
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
