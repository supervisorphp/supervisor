<?php

namespace Test\Functional;

use Zend\XmlRpc\Client;
use Indigo\Supervisor\Connector\ZendConnector;

/**
 * Tests for Zend connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\ZendConnector
 */
class ZendConnectorTest extends AbstractConnectorTest
{
    public function _before()
    {
        $client = new Client($GLOBALS['host']);

        $this->connector = new ZendConnector($client);

        parent::_before();
    }
}
