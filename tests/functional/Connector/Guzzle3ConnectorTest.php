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

use Guzzle\Http\Client;
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
        $client = new Client($GLOBALS['host']);

        $this->connector = new Guzzle3Connector($client);

        parent::_before();
    }
}
