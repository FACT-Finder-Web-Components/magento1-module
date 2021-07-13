<?php

include __DIR__ . DIRECTORY_SEPARATOR . '../../../../../../../../../vendor/autoload.php';

use Omikron\FactFinder\Communication\Credentials as SdkCredentials;

class Omikron_Factfinder_Model_SdkClient_Helper_Credentials
{
    /** @var array */
    private $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function create(): SdkCredentials
    {
        return new SdkCredentials(
            $this->parameters['username'],
            $this->parameters['password'],
            $this->parameters['authenticationPrefix'],
            $this->parameters['authenticationPostfix']
        );
    }
}