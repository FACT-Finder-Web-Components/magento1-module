<?php

use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Client\ClientBuilder;

class Omikron_Factfinder_Model_SdkClient_ClientBuilderConfigurator
{
    /** @var Credentials */
    private $credentials;

    /** @var string */
    private $serverUrl;

    public function __construct(Credentials $credentials, string $serverUrl)
    {
        $this->credentials = $credentials;
        $this->serverUrl = $serverUrl;
    }

    public function getBuilder(): ClientBuilder
    {
        return (new ClientBuilder())
            ->withCredentials($this->credentials)
            ->withServerUrl($this->serverUrl);
    }

}