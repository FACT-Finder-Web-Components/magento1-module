<?php

declare(strict_types=1);

class Omikron_Factfinder_Model_Config_Auth
{
    const PATH_USERNAME = 'factfinder/general/username';
    const PATH_PASSWORD = 'factfinder/general/password';

    const PATH_AUTHENTICATION_PREFIX  = 'factfinder/general/authentication_prefix';
    const PATH_AUTHENTICATION_POSTFIX = 'factfinder/general/authentication_postfix';

    public function getUsername(): string
    {
        return Mage::getStoreConfig(self::PATH_USERNAME);
    }

    public function getPassword(): string
    {
        return Mage::getStoreConfig(self::PATH_PASSWORD);
    }

    public function getAuthenticationPrefix(): ?string
    {
        return Mage::getStoreConfig(self::PATH_AUTHENTICATION_PREFIX);
    }

    public function getAuthenticationPostfix(): ?string
    {
        return Mage::getStoreConfig(self::PATH_AUTHENTICATION_POSTFIX);
    }
}
