<?php

declare(strict_types=1);

use Omikron_Factfinder_Model_ClientNG as ApiClient;

class Omikron_Factfinder_Model_Api_Ng_PushImport implements Omikron_Factfinder_Model_Interface_Api_PushImportInterface
{
    /** @var ClientNG */
    private $apiClient;

    /** @var CommunicationConfig\ */
    private $communicationConfig;

    public function __construct()
    {
        $this->apiClient           = new ApiClient();
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
    }

    public function execute(int $scopeId = null, array $params = []): bool
    {
        if (!Mage::getStoreConfigFlag('factfinder/data_transfer/ff_push_import_enabled', $scopeId)) {
            return false;
        }

        $params += [
            'channel'  => $this->communicationConfig->getChannel($scopeId),
            'quiet'    => 'true',
        ];

        $response = [];
        $endpoint = $this->communicationConfig->getAddress() . sprintf('/rest/%s/import', $this->communicationConfig->getApi());
        foreach (['data','suggest'] as $type) {
            $response = array_merge_recursive($response, $this->apiClient->post($endpoint . "/$type", $params));
        }

        return $response && !(isset($response['errors']) || isset($response['error']));
    }
}
