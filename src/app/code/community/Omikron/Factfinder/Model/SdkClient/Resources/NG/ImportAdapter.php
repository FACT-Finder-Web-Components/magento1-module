<?php

use Omikron_Factfinder_Model_SdkClient_Resources_Import as ImportInterface;
use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Omikron_Factfinder_Model_SdkClient_Resources_GetPushImportDataTypesTrait as PushImportDataTypesTrait;

class Omikron_Factfinder_Model_SdkClient_Resources_NG_ImportAdapter implements ImportInterface
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
            'channel' => $this->communicationConfig->getChannel($scopeId),
            'quiet'   => 'true',
        ];
//        $importTypes = explode(',', Mage::getStoreConfig('factfinder/data_transfer/ff_push_import_types', $scopeId));
        $importTypes = $this->getPushImportDataTypes($scopeId);
        $endpoint = $this->communicationConfig->getAddress() . '/rest/v4/import';

        foreach ($importTypes as $type) {
            $response = $this->sdkClient
                ->setServerUrl($endpoint . DIRECTORY_SEPARATOR . $type)
                ->makePostRequest($params);

            $foo = $type;
//            $response = array_merge_recursive($response, $this->apiClient->post($endpoint . "/$type", $params));
        }

//        $this->sdkClient->setServerUrl()->makePostRequest();
    }
}