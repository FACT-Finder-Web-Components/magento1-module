<?php

include __DIR__ . DIRECTORY_SEPARATOR . '../../../../../../../../vendor/autoload.php';

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron_Factfinder_Model_SdkClient_ClientBuilderConfigurator as ClientBuilderConfiguration;
use Omikron_Factfinder_Model_SdkClient_Helper_Credentials as Credentials;
use Psr\Http\Message\ResponseInterface;
use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Omikron\FactFinder\Communication\ServerUrl;

class Omikron_Factfinder_Model_SdkClient_Client
{
    private const GET = 'GET';
    private const POST = 'POST';

    /** @var \Omikron\FactFinder\Communication\Credentials */
    private $credentials;

    /** @var string */
    private $serverUrl;

    /** @var string */
    private $query;

    /** @var ClientBuilder */
    private $clientBuilder;

    public function init(AuthConfig $authConfig): self
    {
        $this->credentials = $this->getCredentials($authConfig);
        return $this;
    }

    public function makeGetRequest(array $options = []): ResponseInterface
    {
        $url = sprintf('%s?%s', rtrim($this->serverUrl, '/'), $this->query);
        return $this->getClient()->request(self::GET, $url, $options);
    }

    public function makePostRequest(array $options = []): ResponseInterface
    {
        return $this->getClient()->request(self::POST, $this->serverUrl, $options);
    }

    public function setServerUrl(string $serverUrl): self
    {
        $this->serverUrl = (new ServerUrl($serverUrl))->__toString();
        return $this;
    }

    public function setQuery(array $parameters): self
    {
        $this->query = preg_replace('#products%5B\d+%5D%5B(.+?)%5D=#', '\1=', http_build_query($parameters));
        return $this;
    }

    private function getClient(): ClientInterface
    {
        $this->clientBuilder = (new ClientBuilderConfiguration($this->credentials, $this->serverUrl))->getBuilder();
        return $this->clientBuilder->build();
    }

    private function getCredentials(Omikron_Factfinder_Model_Config_Auth $authConfig): \Omikron\FactFinder\Communication\Credentials
    {
        $this->credentials = (new Credentials($authConfig))->create();
        return $this->credentials;
    }
}
