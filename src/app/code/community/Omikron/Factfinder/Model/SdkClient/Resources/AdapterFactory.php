<?php

use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_SdkClient_Resources_ImportInterface as Import;
use Omikron_Factfinder_Model_SdkClient_Resources_ConnectionInterface as Connection;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_SdkClient_Resources_AdapterFactory
{
    private const NG_VERSION = 'ng';
    private const IMPORT_ADAPTER_TYPE = 'Import';
    private const CONNECTION_ADAPTER_TYPE = 'Connection';

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
        $class = $this->getAdapterClass(self::IMPORT_ADAPTER_TYPE);
        return new $class($this->sdkClient, $this->communicationConfig);
    }

    public function getConnectionAdapter(): Connection
    {
        $class = $this->getAdapterClass(self::CONNECTION_ADAPTER_TYPE);
        return new $class($this->sdkClient, $this->communicationConfig);
    }

    private function getAdapterClass(string $adapter): string
    {
        $type = $this->communicationConfig->getVersion() === self::NG_VERSION ? 'NG' : 'Standard';
        return sprintf('Omikron_Factfinder_Model_SdkClient_Resources_%s_%sAdapter', $type, $adapter);
    }
}