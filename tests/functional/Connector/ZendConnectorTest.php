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

use Zend\XmlRpc\Client;
use Indigo\Supervisor\Connector\ZendConnector;

/**
 * Tests for Zend Connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\ZendConnector
 * @group              Supervisor
 * @group              Connector
 */
class ZendConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $client = new Client($GLOBALS['host']);

        $this->connector = new ZendConnector($client);

        parent::_before();
    }

    public function testSupervisorFail()
    {

    }
}
