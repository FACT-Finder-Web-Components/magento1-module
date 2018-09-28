<?php

class Omikron_Factfinder_Helper_Communication extends Mage_Core_Helper_Abstract
{
    // API data
    const API_NAME = 'Search.ff';
    const API_QUERY = 'FACT-Finder version';

    /**
     * @var Omikron_Factfinder_Helper_Data
     */
    private $dataHelper;

    /**
     * Omikron_Factfinder_Helper_Communication constructor.
     */
    public function __construct()
    {
        $this->dataHelper = Mage::helper('factfinder/data');
    }

    /**
     * Update trackingProductNumber field role
     *
     * @param Mage_Core_Model_Store $store
     * @return array
     */
    public function updateFieldRoles($store)
    {
        $conCheck = $this->checkConnection($store);
        if($conCheck['hasFieldRoles']) {
            $this->dataHelper->setFieldRoles($conCheck['fieldRoles']);
        }

        return $conCheck;
    }

    /**
     * Sends HTTP GET request to FACT-Finder. Returns the server response.
     *
     * @param $apiName string
     * @param $params string|array
     * @return mixed
     */
    public function sendToFF($apiName, $params)
    {
        $authentication = $this->dataHelper->getAuthArray();
        $address = $this->dataHelper->getAddress();

        $url = $address . $apiName . "?format=json&" . http_build_query($authentication) . "&";

        if (is_array($params)) {
            $url .= http_build_query($params);
        } else {
            $url .= $params;
        }

        // Send HTTP GET with curl
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_ENCODING, 'Accept-encoding: gzip, deflate');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Receive server response
        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    }

    /**
     * Triggers an ff import on the pushed data
     *
     * @param string $channelName
     * @return bool
     */
    public function pushImport($channelName)
    {
        $response_json = json_decode($this->sendToFF('Import.ff', ['channel' => $channelName, 'type' => 'suggest', 'format' => 'json' , 'quiet' => 'true', 'download' => 'true']), true);
        if(is_array($response_json) && isset($response_json['errors']) && is_array($response_json['errors']) && count($response_json['errors'])) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Update trackingProductNumber field role
     *
     * @param Mage_Core_Model_Store $store
     * @return array
     */
    private function checkConnection($store)
    {
        $result = [];
        $result['success'] = true;
        $result['ff_error_response'] = "";
        $result['ff_error_stacktrace'] = "";
        $result['ff_response_decoded'] = json_decode($this->sendToFF(self::API_NAME, ['query' =>  self::API_QUERY, 'channel' => $this->dataHelper->getChannel($store->getId()), 'verbose' => 'true']), true);

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