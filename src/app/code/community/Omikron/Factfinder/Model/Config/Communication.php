<?php

class Omikron_Factfinder_Model_Config_Communication
{
    const PATH_ENABLED = 'factfinder/general/is_enabled';
    const PATH_ADDRESS = 'factfinder/general/address';
    const PATH_CHANNEL = 'factfinder/general/channel';
    const PATH_VERSION = 'factfinder/general/version';

    public function isChannelEnabled($scopeId = null)
    {
        return Mage::getStoreConfigFlag(self::PATH_ENABLED, $scopeId);
    }

    public function getAddress()
    {
        return Mage::getStoreConfig(self::PATH_ADDRESS);
    }

    public function getChannel($scopeId = null)
    {
        return Mage::getStoreConfig(self::PATH_CHANNEL, $scopeId);
    }

    public function getVersion()
    {
        return Mage::getStoreConfig(self::PATH_VERSION);
    }
}
