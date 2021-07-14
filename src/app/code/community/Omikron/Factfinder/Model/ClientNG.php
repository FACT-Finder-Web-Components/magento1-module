<?php

declare(strict_types=1);

use Omikron_Factfinder_Exception_ResponseException as ResponseException;
use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Omikron_Factfinder_Model_Http_Adapter_Curl as CurlAdapter;
use Zend_Http_Client as HttpClient;
use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;

class Omikron_Factfinder_Model_ClientNG implements Omikron_Factfinder_Model_Interface_ClientInterface
{
    /** @var AuthConfig */
    private $authConfig;

    /** @var Credentials */
    private $credentials;

    /** @var SdkClient */
    private $sdkClient;

    public function __construct(Credentials $credentials = null)
    {
        $this->credentials = $credentials;
        $this->authConfig  = Mage::getModel('factfinder/config_auth');
        $this->sdkClient   = Mage::getModel('factfinder/sdkClient_client');
    }

    /**
     * @param string $endpoint
     * @param array  $params
     *
     * @return array
     * @throws Zend_Http_Client_Exception
     * @throws Zend_Uri_Exception
     */
    public function get(string $endpoint, array $params): array
    {
        $this->sdkClient->init($this->authConfig);

        try {
            $query = preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', http_build_query($params));

            $response = $this->sdkClient
                ->setServerUrl($endpoint)
                ->setQuery($query)
                ->makeGetRequest();

            if ($response->isSuccessful()) {
                return (array) Mage::helper('core')->jsonDecode($response->getBody());
            }

            throw new ResponseException($response->getBody(), $response->getStatus());
        } catch (Zend_Json_Exception $e) {
            return (array) $response->getBody();
        }
    }

    /**
     * @param string $endpoint
     * @param array  $params
     *
     * @return array
     * @throws Zend_Http_Client_Exception
     * @throws Zend_Uri_Exception
     */
    public function post(string $endpoint, array $params): array
    {
        $client = $this->initClient();
        try {
            $client->setRawData(Mage::helper('core')->jsonEncode($params), 'application/json');
            $client->setUri($endpoint);
            $response = $client->request(HttpClient::POST);

            if ($response->getStatus() >= 200 && $response->getStatus() < 300) {
                return (array) Mage::helper('core')->jsonDecode($response->getBody());
            }

            throw new ResponseException($response->getBody(), $response->getStatus());
        } catch (Zend_Json_Exception $e) {
            return (array) $response->getBody();
        }
    }

    private function initClient(): HttpClient
    {
        $client = new HttpClient();
        $client->setHeaders('Accept', 'application/json');
        $client->setAuth($this->authConfig->getUsername(), $this->authConfig->getPassword());
        $client->setAdapter(new CurlAdapter());
        return $client;
    }
}
