<?php

use Omikron_Factfinder_Exception_ResponseException as ResponseException;
use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Varien_Http_Adapter_Curl as CurlAdapter;
use Varien_Http_Client as HttpClient;

class Omikron_Factfinder_Model_Client implements Omikron_Factfinder_Model_Interface_ClientInterface
{
    /** @var AuthConfig */
    private $authConfig;

    /** @var Credentials */
    private $credentials;

    public function __construct(Credentials $credentials = null)
    {
        $this->credentials = $credentials;
        $this->authConfig = Mage::getModel('factfinder/config_auth');
    }

    /**
     * @param string $endpoint
     * @param array  $params
     *
     * @return array
     * @throws ResponseException
     * @throws ResponseException
     */
    public function get(string $endpoint, array $params): array
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

    public function post(string $endpoint, array $params): array
    {
        throw new \BadMethodCallException('FACT-Finder API for version 7.3 and lower does not accept POST requests');
    }

    private function getCredentials(AuthConfig $config)
    {
        return $this->credentials ? $this->credentials : new Credentials(
            $config->getUsername(),
            $config->getPassword(),
            $config->getAuthenticationPrefix(),
            $config->getAuthenticationPostfix()
        );
    }
}
