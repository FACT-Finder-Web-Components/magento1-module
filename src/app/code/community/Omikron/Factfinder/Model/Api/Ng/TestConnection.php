<?php

use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Model_ClientNG as ApiClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Api_Ng_TestConnection implements Omikron_Factfinder_Model_Interface_Api_TestConnectionInterface
{
    /** @var CommunicationConfig */
    protected $communicationConfig;

    /** @var string */
    protected $apiQuery = 'FACT-Finder version';

    public function __construct()
    {
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
    }

    public function execute(string $serverUrl, array $params, Credentials $credentials): bool
    {
        $channel  = $params['channel'] ?? $this->communicationConfig->getChannel();
        $endpoint = sprintf('/rest/%s/search/%s', $this->communicationConfig->getApi(), $channel);
        $client   = new ApiClient($credentials);
        $client->get(rtrim($serverUrl, '/') . $endpoint, $params + ['query' => $this->apiQuery]);
        return true;
    }
}
