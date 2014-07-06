<?php

namespace Test\Functional;

use Zend\XmlRpc\Client;
use Indigo\Supervisor\Connector\Zend;

/**
 * Tests for Zend connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\Zend
 */
class ZendTest extends AbstractConnectorTest
{
    public function _before()
    {
        $client = new Client($GLOBALS['host']);

        $this->connector = new Zend($client);

        parent::_before();
    }
}
