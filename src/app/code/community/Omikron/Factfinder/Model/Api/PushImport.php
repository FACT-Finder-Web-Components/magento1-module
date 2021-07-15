<?php

use Omikron_Factfinder_Model_Client as ApiClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_SdkClient_Resources_AdapterFactory as AdapterFactory;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;

class Omikron_Factfinder_Model_Api_PushImport implements Omikron_Factfinder_Model_Interface_Api_PushImportInterface
{
    /** @var ApiClient */
    private $apiClient;

    /** @var CommunicationConfig */
    private $communicationConfig;

    /** @var string */
    private $apiName = 'Import.ff';

    /** @var SdkClient */
    protected $sdkClient;

    /** @var AuthConfig */
    private $authConfig;

    public function __construct()
    {
        $this->apiClient           = new ApiClient();
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
        $this->sdkClient   = Mage::getModel('factfinder/sdkClient_client');
        $this->authConfig  = Mage::getModel('factfinder/config_auth');
    }

    /**
     * @param null|int  $scopeId
     * @param array     $params
     *
     * @return bool
     */
    public function execute(int $scopeId = null, array $params = []): bool
    {
        if (!Mage::getStoreConfigFlag('factfinder/data_transfer/ff_push_import_enabled', $scopeId)) {
            return false;
        }

        $this->sdkClient->init($this->authConfig);
        $importAdapter = (new AdapterFactory($this->sdkClient, $this->communicationConfig))->getImportAdapter();
        $result = $importAdapter->import($scopeId);

//        $params += [
//            'channel'  => $this->communicationConfig->getChannel($scopeId),
//            'quiet'    => 'true',
//            'download' => 'true',
//        ];
//
//        $response = [];
//        $importTypes = explode(',', Mage::getStoreConfig('factfinder/data_transfer/ff_push_import_types', $scopeId));
//        $endpoint = $this->communicationConfig->getAddress() . '/' . $this->apiName;
//        foreach ($importTypes as $type) {
//            $params['type'] = $type;
//            $response       = array_merge_recursive($response, $this->apiClient->sendRequest($endpoint, $params));
//        }
//
//        return $response && !($response['errors'] ?? $response['error'] ?? false);
    }
}
