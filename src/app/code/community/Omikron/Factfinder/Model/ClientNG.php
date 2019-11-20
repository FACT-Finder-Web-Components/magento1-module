<?php

declare(strict_types=1);

use Omikron_Factfinder_Exception_ResponseException as ResponseException;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Varien_Http_Adapter_Curl as CurlAdapter;
use Varien_Http_Client as HttpClient;

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
            $client->setUri($endpoint . '?' . $query);
            $client->getUri()->setQuery(
                preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', http_build_query($params))
            );
            $response = $client->request(HttpClient::GET);

            if ($response->getStatus() >= 200 && $response->getStatus() < 300) {
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
        $client->setHeaders('Authorization', $this->getCredentials($this->authConfig));

        $curlAdapter = new CurlAdapter();
        $curlAdapter->setOptions([CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1]);
        $client->setAdapter($curlAdapter);

        return $client;
    }

    private function getCredentials(AuthConfig $config): Credentials
    {
        return $this->credentials ? $this->credentials : new Credentials(
            $config->getUsername(),
            $config->getPassword(),
            $config->getAuthenticationPrefix(),
            $config->getAuthenticationPostfix()
        );
    }
}
