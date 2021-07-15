<?php

use Omikron_Factfinder_Model_SdkClient_Resources_Import as ImportInterface;
use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_SdkClient_Resources_Standard_ImportAdapter implements ImportInterface
{
    /** @var SdkClient */
    private $sdkClient;

    /** @var CommunicationConfig  */
    private $communicationConfig;

    public function __construct(Omikron_Factfinder_Model_SdkClient_Client $sdkClient, Omikron_Factfinder_Model_Config_Communication $communicationConfig)
    {
        $this->sdkClient = $sdkClient;
        $this->communicationConfig = $communicationConfig;
    }

    public function import(int $scopeId = null)
    {
        return 'standard';
    }
}