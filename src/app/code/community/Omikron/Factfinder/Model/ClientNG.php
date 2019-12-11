<?php

declare(strict_types=1);

use Omikron_Factfinder_Exception_ResponseException as ResponseException;
use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Zend_Http_Client as HttpClient;

class Omikron_Factfinder_Model_ClientNG implements Omikron_Factfinder_Model_Interface_ClientInterface
{
    /** @var AuthConfig */
    private $authConfig;

    /** @var Credentials */
    private $credentials;

    public function __construct(Credentials $credentials = null)
    {
        $this->credentials = $credentials;
        $this->authConfig  = Mage::getModel('factfinder/config_auth');
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
        $client = $this->initClient();
        try {
            $query = preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', http_build_query($params));
            $client->setUri($endpoint);
            $client->getUri()->setQuery($query);
            $response = $client->request(HttpClient::GET);

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
        $client->setAdapter(new class() extends Varien_Http_Adapter_Curl {
            public function read() {
                return str_replace('HTTP/2 ', 'HTTP/1.1 ', parent::read());
            }
        });
        return $client;
    }
}
