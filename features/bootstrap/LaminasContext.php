<?php

use Supervisor\Connector\Laminas;
use Supervisor\Supervisor;
use Laminas\XmlRpc\Client;
use Laminas\Http\Client as HttpClient;

class LaminasContext extends FeatureContext
{
    protected function setUpConnector()
    {
        $client = new Client('http://127.0.0.1:9001/RPC2');
        $httpClient = $client->getHttpClient();
        if ($httpClient) {
            $httpClient->setAuth('user', '123', HttpClient::AUTH_BASIC);
        }

        $connector = new Laminas($client);
        $this->supervisor = new Supervisor($connector);
    }
}
