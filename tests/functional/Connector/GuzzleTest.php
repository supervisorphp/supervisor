<?php

namespace Test\Functional;

use GuzzleHttp\Client;
use Indigo\Supervisor\Connector\Guzzle;

/**
 * Tests for Guzzle connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\Guzzle
 */
class GuzzleTest extends AbstractConnectorTest
{
    public function _before()
    {
        $client = new Client(array('base_url' => $GLOBALS['host']));

        $this->connector = new Guzzle($client);

        parent::_before();
    }
}
