<?php

include __DIR__ . DIRECTORY_SEPARATOR . '../../../../../../../../vendor/autoload.php';

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron_Factfinder_Model_SdkClient_ClientBuilderConfigurator as ClientBuilderConfiguration;
use Mage_Core_Controller_Request_Http as HttpRequest;
use Omikron_Factfinder_Model_SdkClient_Helper_Credentials as Credentials;
use Psr\Http\Message\ResponseInterface;
use Omikron\FactFinder\Communication\Client\ClientInterface;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;

class Omikron_Factfinder_Model_SdkClient_Client
{
    private const GET = 'GET';
    private const POST = 'POST';

    /** @var HttpRequest */
    private $request;
    /** @var array */
    private $parameters;
    /** @var \Omikron\FactFinder\Communication\Credentials */
    private $credentials;
    /** @var string */
    private $serverUrl;
    /** @var string */
    private $query;
    /** @var string */
    private $channel;
    /** @var string */
    private $version;
    /** @var ClientBuilder */
    private $clientBuilder;

    public function init(AuthConfig $authConfig): self
    {
        $this->credentials = $this->getCredentials($authConfig);

        return $this;
    }

    public function makeGetRequest(array $options = []): ResponseInterface
    {
        $client = $this->getClient();
        $url = sprintf('%s?%s', $this->serverUrl, $this->query);

        return $client->request(self::GET, $url, $options);
    }

    public function makePostRequest(array $options = [])
    {

    }

    public function setServerUrl(string $serverUrl): self
    {
        $this->serverUrl = $serverUrl;

        return $this;
    }

    public function setQuery(string $query): self
    {
        $this->query = $query;

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
