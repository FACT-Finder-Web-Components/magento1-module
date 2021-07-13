<?php

include __DIR__ . DIRECTORY_SEPARATOR . '../../../../../../../../vendor/autoload.php';

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron_Factfinder_Model_SdkClient_ClientBuilderConfigurator as ClientBuilderConfiguration;
use Mage_Core_Controller_Request_Http as HttpRequest;
use Omikron_Factfinder_Model_SdkClient_Helper_Credentials as Credentials;
use Psr\Http\Message\ResponseInterface;


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
    private $channel;
    /** @var string */
    private $version;
    /** @var ClientBuilder */
    private $clientBuilder;

    public function init(HttpRequest $request): self
    {
        $this->request = $request;
        $this->parameters = $this->request->getParams();
        $this->credentials = $this->getCredentials();
        $this->serverUrl = $this->parameters['serverUrl'];
        $this->channel = $this->parameters['channel'];
        $this->version = $this->parameters['version'];
        $this->clientBuilder = (new ClientBuilderConfiguration($this->credentials, $this->serverUrl, $this->version))->getBuilder();

        return $this;
    }

    public function makeGetRequest(array $options = []): ResponseInterface
    {
        $client = $this->clientBuilder->build();

        return $client->request(self::GET, $this->serverUrl, $options);
    }

    private function getParameters()
    {
        return $this->request->getParams();
    }

    private function getCredentials(): \Omikron\FactFinder\Communication\Credentials
    {
        $this->credentials = (new Credentials($this->parameters))->create();

        return $this->credentials;

    }

}
