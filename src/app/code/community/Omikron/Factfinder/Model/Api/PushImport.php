<?php

use Omikron_Factfinder_Model_Client as ApiClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Api_PushImport
{
    /** @var ApiClient */
    private $apiClient;

    /** @var CommunicationConfig */
    private $communicationConfig;

    /** @var string */
    private $apiName = 'Import.ff';

    public function __construct()
    {
        $this->apiClient           = Mage::getModel('factfinder/client');
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
    }

    /**
     * @param null  $scopeId
     * @param array $params
     *
     * @return bool
     */
    public function execute($scopeId = null, array $params = [])
    {
        if (!Mage::getStoreConfigFlag('factfinder/data_transfer/ff_push_import_enabled', $scopeId)) {
            return false;
        }

        $params += [
            'channel'  => $this->communicationConfig->getChannel($scopeId),
            'quiet'    => 'true',
            'download' => 'true',
        ];

        $response = [];
        $endpoint = $this->communicationConfig->getAddress() . '/' . $this->apiName;
        foreach (['data','suggest'] as $type) {
            $params['type'] = $type;
            $response       = array_merge_recursive($response, $this->apiClient->sendRequest($endpoint, $params));
        }

        return $response && !(isset($response['errors']) || isset($response['error']));
    }
}
