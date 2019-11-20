<?php

declare(strict_types=1);

class Omikron_Factfinder_Model_Api_TrackingFactory extends Omikron_Factfinder_Model_AbstractApiFactory
{
    public function create(array $params = [])
    {
        if ($this->config->isNg()) {
            return Mage::getModel('factfinder/api_ng_tracking');
        }

        return Mage::getModel('factfinder/api_testConnection');
    }
}
