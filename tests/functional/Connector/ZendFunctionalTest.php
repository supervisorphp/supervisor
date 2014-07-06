<?php

namespace Indigo\Supervisor\Connector;

use Zend\XmlRpc\Client;
use Codeception\TestCase\Test;

/**
 * Tests for Zend connector
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 *
 * @coversDefaultClass Indigo\Supervisor\Connector\Zend
 */
class ZendFunctionalTest extends AbstractConnectorFunctionalTest
{
    public function _before()
    {
        $client = new Client($GLOBALS['host']);

        $this->connector = new Zend($client);
    }
}
