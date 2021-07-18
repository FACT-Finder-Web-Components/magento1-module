<?php

use Omikron_Factfinder_Model_SdkClient_Resources_ConnectionInterface as ConnectionInterface;
use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_SdkClient_Resources_NG_ConnectionAdapter implements ConnectionInterface
{
    /** @var SdkClient */
    private $sdkClient;

    /** @var CommunicationConfig */
    private $communicationConfig;

    public function __construct(Omikron_Factfinder_Model_SdkClient_Client $sdkClient, Omikron_Factfinder_Model_Config_Communication $communicationConfig)
    {
        $this->sdkClient = $sdkClient;
        $this->communicationConfig = $communicationConfig;
    }

    public function connect()
    {
        $channel  = $this->communicationConfig->getChannel();
        $parameters  = ['channel' => $channel, 'query' => 'FACT-Finder version'];
        $endpoint = $this->communicationConfig->getAddress() . DIRECTORY_SEPARATOR . 'rest/v4/search/' . $channel;
        $this->sdkClient
            ->setServerUrl($endpoint)
            ->setQuery($parameters)
            ->makeGetRequest();
    }
}