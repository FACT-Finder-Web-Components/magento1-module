<?php

include __DIR__ . DIRECTORY_SEPARATOR . '../../../../../../../../vendor/autoload.php';

use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Client\ClientBuilder;

class Omikron_Factfinder_Model_SdkClient_ClientBuilderConfigurator
{
    /** @var Credentials */
    private $credentials;

    /** @var string */
    private $serverUrl;

    /** @var string */
    private $version;

    /**
     * Omikron_Factfinder_Model_SdkClient_ClientBuilderConfigurator constructor.
     * @param Credentials $credentials
     * @param string $serverUrl
     * @param string $version
     */
    public function __construct(Credentials $credentials, string $serverUrl, string $version)
    {
        $this->credentials = $credentials;
        $this->serverUrl = $serverUrl;
        $this->version = $version;
    }

    public function getBuilder(): ClientBuilder
    {
        return (new ClientBuilder())
            ->withCredentials($this->credentials)
            ->withVersion($this->version)
            ->withServerUrl($this->serverUrl);
    }

}