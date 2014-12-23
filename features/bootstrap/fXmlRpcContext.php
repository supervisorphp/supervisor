<?php

use Indigo\Supervisor\Connector\XmlRpc;
use Indigo\Supervisor\Supervisor;
use fXmlRpc\Client;
use fXmlRpc\Transport\Guzzle4Bridge;
use GuzzleHttp\Client as GuzzleClient;

class fXmlRpcContext extends FeatureContext
{
    protected function setUpConnector()
    {
        $client = new Client(
            'http://127.0.0.1:9001/RPC2',
            new Guzzle4Bridge(new GuzzleClient(['defaults' => ['auth' => ['user', '123']]]))
        );

        $connector = new XmlRpc($client);
        $this->supervisor = new Supervisor($connector);
    }
}
