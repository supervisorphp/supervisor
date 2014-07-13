<?php

namespace Test\Functional;

use GuzzleHttp\Client;
use Indigo\Supervisor\Connector\GuzzleConnector;

/**
 * Tests for Guzzle connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\GuzzleConnector
 */
class GuzzleConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $client = new Client(array('base_url' => $GLOBALS['host']));

        $this->connector = new GuzzleConnector($client);

        parent::_before();
    }
}
