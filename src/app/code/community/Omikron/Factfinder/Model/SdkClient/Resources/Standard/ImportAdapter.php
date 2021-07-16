<?php

use Omikron_Factfinder_Model_SdkClient_Resources_Import as ImportInterface;
use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Omikron_Factfinder_Model_SdkClient_Resources_GetPushImportDataTypesTrait as PushImportDataTypesTrait;

class Omikron_Factfinder_Model_SdkClient_Resources_Standard_ImportAdapter implements ImportInterface
{
    use PushImportDataTypesTrait;

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
        $params = [
            'channel'  => $this->communicationConfig->getChannel($scopeId),
            'quiet'    => 'true',
            'download' => 'true',
        ];
        $response = [];
        $importTypes = $this->getPushImportDataTypes($scopeId);
        $endpoint = $this->communicationConfig->getAddress() . '/v4';
        foreach ($importTypes as $type) {
            $params['type'] = $type;
            $response[] = $this->sdkClient->setServerUrl($endpoint)->makeGetRequest($params);
        }

        array_map(function ($item) {
            if ($item->getCode() != 200) {
                return 1;
            }
        }, $response);

        return 0;
    }
}