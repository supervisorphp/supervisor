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

use Indigo\Supervisor\Connector\fXmlRpcConnector;
use fXmlRpc\Client;

/**
 * Tests for fXmlRpc Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\fXmlRpcConnector
 * @group              Supervisor
 * @group              Connector
 */
class fXmlRpcConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $client = new Client($GLOBALS['host']);

        $this->connector = new fXmlRpcConnector($client);

        parent::_before();
    }
}
