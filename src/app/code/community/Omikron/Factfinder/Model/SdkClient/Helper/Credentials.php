<?php

include __DIR__ . '/../../../../../../../../../vendor/autoload.php';

use Omikron\FactFinder\Communication\Credentials as SdkCredentials;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;

class Omikron_Factfinder_Model_SdkClient_Helper_Credentials
{
    /** @var AuthConfig */
    private $config;

    public function __construct(AuthConfig $config)
    {
        $this->config = $config;
    }

    public function create(): SdkCredentials
    {
        return new SdkCredentials(
            $this->config->getUsername(),
            $this->config->getPassword(),
            $this->config->getAuthenticationPrefix() ?? '',
            $this->config->getAuthenticationPostfix() ?? ''
        );
    }
}