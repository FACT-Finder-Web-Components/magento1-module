<?php

use Mage_Core_Model_Store as Store;
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
     * Sends HTTP GET request to FACT-Finder. Returns the server response.
     *
     * @param string $apiName
     * @param array  $params
     *
     * @return array
     * @throws Zend_Http_Client_Exception
     * @throws Zend_Uri_Exception
     */
    public function sendToFF($apiName, array $params)
    {
        $client = new HttpClient();
        $client->setUri($this->dataHelper->getAddress() . $apiName);
        $query = http_build_query($params + ['format' => 'json'] + $this->dataHelper->getAuthArray());
        $client->getUri()->setQuery(preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', $query));

        try {
            $result = (array) Mage::helper('core')->jsonDecode($client->request(HttpClient::GET)->getBody());
        } catch (Zend_Json_Exception $e) {
            $result = (array) $client->request(HttpClient::GET)->getBody();
        }

        return $result;
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
        } catch (Zend_Exception $e) {
            Mage::logException($e);
            return false;
        }
    }

    /**
     * Update trackingProductNumber field role
     *
     * @param Store $store
     *
     * @return array
     * @throws Mage_Core_Exception
     * @throws Zend_Exception
     */
    public function checkConnection(Store $store)
    {
        $response = $this->sendToFF(self::API_NAME, [
            'query'   => self::API_QUERY,
            'channel' => $this->dataHelper->getChannel($store->getId()),
            'verbose' => 'true',
        ]);

        if (isset($response['error'])) {
            Mage::throwException($response['error']);
        }

        return $response;
    }
}
