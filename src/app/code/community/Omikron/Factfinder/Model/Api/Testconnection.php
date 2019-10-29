<?php

use Omikron_Factfinder_Model_Client as ApiClient;
use Omikron_Factfinder_Exception_ResponseException as ResponseException;

class Omikron_Factfinder_Model_Api_Testconnection
{
    /** @var ClientInterface */
    private $apiClient;

    /** @var string */
    private $apiQuery = 'FACT-Finder version';

    public function __construct()
    {
        $this->apiClient = Mage::getModel('factfinder/client');
    }

    /**
     * @param string $serverUrl
     * @param array  $params
     *
     * @return bool
     * @throws ResponseException
     */
    public function execute($serverUrl, $params)
    {
        $this->apiClient->sendRequest(rtrim($serverUrl, '/') . '/Search.ff', $params + ['query' => $this->apiQuery]);

        return true;
    }
}
