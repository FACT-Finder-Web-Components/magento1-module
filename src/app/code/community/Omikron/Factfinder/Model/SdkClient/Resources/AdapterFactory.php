<?php

use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_SdkClient_Resources_Import as Import;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_SdkClient_Resources_AdapterFactory
{
    private const NG_VERSION = 'ng';

    /** @var SdkClient */
    private $sdkClient;

    /** @var CommunicationConfig */
    private $communicationConfig;

    public function __construct(Omikron_Factfinder_Model_SdkClient_Client $sdkClient, Omikron_Factfinder_Model_Config_Communication $communicationConfig)
    {
        $this->sdkClient = $sdkClient;
        $this->communicationConfig = $communicationConfig;
    }

    public function getImportAdapter(): Import
    {
        $class = $this->getAdapterClass();

        return new $class($this->sdkClient, $this->communicationConfig);
    }

    private function getAdapterClass(): string
    {
        switch ($this->communicationConfig->getVersion()) {
            case self::NG_VERSION:
                return 'Omikron_Factfinder_Model_SdkClient_Resources_NG_ImportAdapter';
            default:
                return 'Omikron_Factfinder_Model_SdkClient_Resources_Standard_ImportAdapter';
        }
    }
}