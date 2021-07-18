<?php

use Omikron_Factfinder_Model_SdkClient_Resources_ConnectionInterface as ConnectionInterface;
use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_SdkClient_Resources_Standard_ConnectionAdapter implements ConnectionInterface
{
    /** @var string */
    protected $apiQuery = 'FACT-Finder version';

    /** @var SdkClient */
    private $sdkClient;

    /** @var CommunicationConfig */
    private $communicationConfig;

    /**
     * Omikron_Factfinder_Model_SdkClient_Resources_Standard_ConnectionAdapter constructor.
     * @param Omikron_Factfinder_Model_SdkClient_Client $sdkClient
     * @param Omikron_Factfinder_Model_Config_Communication $communicationConfig
     */
    public function __construct(Omikron_Factfinder_Model_SdkClient_Client $sdkClient, Omikron_Factfinder_Model_Config_Communication $communicationConfig)
    {
        $this->sdkClient = $sdkClient;
        $this->communicationConfig = $communicationConfig;
    }

    public function connect()
    {
        $endpoint = $this->communicationConfig->getAddress() . DIRECTORY_SEPARATOR . 'Search.ff';
        $parameters = [
            'channel' => $this->communicationConfig->getChannel(),
            'query' => $this->apiQuery
        ];
        $this->sdkClient
            ->setServerUrl($endpoint)
            ->setQuery($parameters)
            ->makeGetRequest();
    }
}