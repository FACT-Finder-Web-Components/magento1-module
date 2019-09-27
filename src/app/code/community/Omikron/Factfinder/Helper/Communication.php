<?php

use Varien_Http_Client as HttpClient;

class Omikron_Factfinder_Helper_Communication extends Mage_Core_Helper_Abstract
{
    const API_NAME  = 'Search.ff';
    const API_QUERY = 'FACT-Finder version';

    /** @var Omikron_Factfinder_Helper_Data */
    private $dataHelper;

    public function __construct()
    {
        $this->dataHelper = Mage::helper('factfinder');
    }

    /**
     * Update trackingProductNumber field role
     *
     * @param Mage_Core_Model_Store $store
     *
     * @return array
     */
    public function updateFieldRoles($store)
    {
        $conCheck = $this->checkConnection($store);
        if ($conCheck['hasFieldRoles']) {
            $this->dataHelper->setFieldRoles($conCheck['fieldRoles']);
        }
        return $conCheck;
    }

    /**
     * Sends HTTP GET request to FACT-Finder. Returns the server response.
     *
     * @param string $apiName
     * @param array  $params
     *
     * @return array
     * @throws Zend_Http_Client_Exception
     */
    public function sendToFF($apiName, array $params)
    {
        $client = new HttpClient();
        $client->setUri($this->dataHelper->getAddress() . $apiName);
        $client->setParameterGet($params + ['format' => 'json'] + $this->dataHelper->getAuthArray());
        return (array) Mage::helper('core')->jsonDecode($client->request(HttpClient::GET)->getBody());
    }

    /**
     * Triggers an ff import on the pushed data
     *
     * @param string $channelName
     *
     * @return bool
     */
    public function pushImport($channelName)
    {
        try {
            $response = $this->sendToFF('Import.ff', [
                'channel'  => $channelName,
                'type'     => 'suggest',
                'quiet'    => 'true',
                'download' => 'true',
            ]);

            if (isset($response['errors']) && is_array($response['errors']) && count($response['errors'])) {
                return false;
            } else {
                return true;
            }
        } catch (Zend_Http_Client_Exception $e) {
            Mage::logException($e);
            return false;
        }
    }

    /**
     * Update trackingProductNumber field role
     *
     * @param Mage_Core_Model_Store $store
     * @return array
     */
    public function checkConnection($store)
    {
        $response = $this->sendToFF(self::API_NAME, [
            'query'   => self::API_QUERY,
            'channel' => $this->dataHelper->getChannel($store->getId()),
            'verbose' => 'true',
        ]);

        $result = [];
        $result['success'] = true;
        $result['ff_error_response'] = '';
        $result['ff_error_stacktrace'] = '';
        $result['ff_response_decoded'] = $response;

        if (!is_array($result['ff_response_decoded'])) {
            $result['ff_response_decoded'] = [];
            $result['success'] = false;
        }
        if (isset($result['ff_response_decoded']['error'])) {
            $result['ff_error_response'] = $result['ff_response_decoded']['error'];
            if(isset($result['ff_response_decoded']['stacktrace'])) $result['ff_error_stacktrace'] = explode('at', $result['ff_response_decoded']['stacktrace'])[0];
            $result['success'] = false;
        }
        if($result['success'] && isset($result['ff_response_decoded']['searchResult']) && isset($result['ff_response_decoded']['searchResult']['fieldRoles'])) {
            $result['hasFieldRoles'] = true;
            $result['fieldRoles'] = json_encode($result['ff_response_decoded']['searchResult']['fieldRoles']);
        }
        else {
            $result['hasFieldRoles'] = false;
            $result['fieldRoles'] = false;
        }

        return $result;
    }
}
