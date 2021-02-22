<?php

declare(strict_types=1);

class Omikron_Factfinder_Model_Config_Communication
{
    const PATH_ENABLED = 'factfinder/general/is_enabled';
    const PATH_ADDRESS = 'factfinder/general/address';
    const PATH_CHANNEL = 'factfinder/general/channel';
    const PATH_VERSION = 'factfinder/general/version';
    const PATH_API     = 'factfinder/general/api';

    public function isChannelEnabled($scopeId = null): bool
    {
        return Mage::getStoreConfigFlag(self::PATH_ENABLED, $scopeId);
    }

    public function getAddress(): string
    {
        return Mage::getStoreConfig(self::PATH_ADDRESS);
    }

    public function getChannel($scopeId = null): string
    {
        return Mage::getStoreConfig(self::PATH_CHANNEL, $scopeId);
    }

    public function getVersion(): string
    {
        return Mage::getStoreConfig(self::PATH_VERSION);
    }

    public function getApi(): string
    {
        return Mage::getStoreConfig(self::PATH_API);
    }

    public function isNg(): bool
    {
        return Mage::getStoreConfig(self::PATH_VERSION) === 'ng';
    }
}
