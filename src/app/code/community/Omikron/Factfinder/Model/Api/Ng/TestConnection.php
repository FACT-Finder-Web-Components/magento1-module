<?php

use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Omikron_Factfinder_Model_ClientNG as ClientNG;

class Omikron_Factfinder_Model_Api_Ng_TestConnection implements Omikron_Factfinder_Model_Interface_Api_TestConnectionInterface
{
    /** @var CommunicationConfig\ */
    private $communicationConfig;

    /** @var string */
    private $apiQuery = 'FACT-Finder version';

    public function __construct()
    {
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
    }

    public function execute(string $serverUrl, array $params, Credentials $credentials): bool
    {
        $channel  = isset($params['channel']) ? $params['channel'] : $this->communicationConfig->getChannel();
        $endpoint = rtrim($serverUrl, '/') . sprintf('/rest/%s/search/%s', $this->communicationConfig->getApi(), $channel);
        $client   = new ClientNG($credentials);
        $client->get($endpoint, $params + ['query' => $this->apiQuery]);

        return true;
    }
}
