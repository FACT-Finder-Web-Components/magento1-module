<?php

use Omikron_Factfinder_Exception_ResponseException as ResponseException;
use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Varien_Http_Adapter_Curl as CurlAdapter;
use Varien_Http_Client as HttpClient;

class Omikron_Factfinder_Model_Client
{
    /** @var AuthConfig */
    private $authConfig;

    public function __construct()
    {
        $this->authConfig = Mage::getModel('factfinder/config_auth');
    }

    public function sendRequest($endpoint, array $params)
    {
        $client = new HttpClient();
        $client->setHeaders('Accept', 'application/json');
        $params = ['format' => 'json'] + $params + $this->getCredentials($this->authConfig)->toArray();
        $client->setUri($endpoint);
        $client->getUri()->setQuery(preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', http_build_query($params)));

        $curlAdapter = new CurlAdapter();
        $curlAdapter->setOptions([CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1]);
        $client->setAdapter($curlAdapter);

        try {
            $response = $client->request(HttpClient::GET);
            if ($response->getStatus() >= 200 && $response->getStatus() < 300) {
                return (array) Mage::helper('core')->jsonDecode($response->getBody());
            }

            throw new ResponseException($response->getBody(), $response->getStatus());
        } catch (Zend_Json_Exception $e) {
            return (array) $$response->getBody();
        }
    }

    private function getCredentials(AuthConfig $config)
    {
        return new Credentials(
            $config->getUsername(),
            $config->getPassword(),
            $config->getAuthenticationPrefix(),
            $config->getAuthenticationPostfix()
        );
    }
}
