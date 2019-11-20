<?php

use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Model_Client as Client;

class Omikron_Factfinder_Model_Api_TestConnection implements Omikron_Factfinder_Model_Interface_Api_TestConnectionInterface
{
    /** @var string */
    private $apiQuery = 'FACT-Finder version';

    public function execute(string $serverUrl, array $params, Credentials $credentials): bool
    {
        $client = new Client($credentials);
        $client->get(rtrim($serverUrl, '/') . '/Search.ff', $params + ['query' => $this->apiQuery]);

        return true;
    }
}
