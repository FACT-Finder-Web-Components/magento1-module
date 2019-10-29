<?php

class Omikron_Factfinder_Model_Config_Auth
{
    const PATH_USERNAME = 'factfinder/general/username';
    const PATH_PASSWORD = 'factfinder/general/password';

    const PATH_AUTHENTICATION_PREFIX  = 'factfinder/general/authentication_prefix';
    const PATH_AUTHENTICATION_POSTFIX = 'factfinder/general/authentication_postfix';

    public function getUsername()
    {
        return Mage::getStoreConfig(self::PATH_USERNAME);
    }

    public function getPassword()
    {
        return Mage::getStoreConfig(self::PATH_PASSWORD);
    }

    public function getAuthenticationPrefix()
    {
        return Mage::getStoreConfig(self::PATH_AUTHENTICATION_PREFIX);
    }

    public function getAuthenticationPostfix()
    {
        return Mage::getStoreConfig(self::PATH_AUTHENTICATION_POSTFIX);
    }
}
