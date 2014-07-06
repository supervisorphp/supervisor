<?php

namespace Test\Functional;

use Guzzle\Http\Client;
use Indigo\Supervisor\Connector\Guzzle3;

/**
 * Tests for Guzzle connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\Guzzle
 */
class Guzzle3Test extends AbstractConnectorTest
{
    public function _before()
    {
        $client = new Client($GLOBALS['host']);

        $this->connector = new Guzzle3($client);

        parent::_before();
    }
}
