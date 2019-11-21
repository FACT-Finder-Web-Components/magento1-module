<?php

use Omikron_Factfinder_Model_Client as ApiClient;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Api_PushImport implements Omikron_Factfinder_Model_Interface_Api_PushImportInterface
{
    /** @var ApiClient */
    private $apiClient;

    /** @var CommunicationConfig */
    private $communicationConfig;

    /** @var string */
    private $apiName = 'Import.ff';

    public function __construct()
    {
        $this->apiClient           = new ApiClient();
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
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
