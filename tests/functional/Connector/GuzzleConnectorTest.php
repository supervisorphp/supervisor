<?php

/*
 * This file is part of the Indigo Supervisor package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Functional;

use GuzzleHttp\Client;
use Indigo\Supervisor\Connector\GuzzleConnector;

/**
 * Tests for Guzzle Connector
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
        $client = new Client(array('base_url' => $GLOBALS['host']));

        $this->connector = new GuzzleConnector($client);

        parent::_before();
    }
}
