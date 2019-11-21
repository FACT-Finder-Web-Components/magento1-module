<?php

declare(strict_types=1);

use Omikron_Factfinder_Model_Interface_Api_TrackingInterface as TrackingInterface;

class Omikron_Factfinder_Model_Api_TrackingFactory extends Omikron_Factfinder_Model_Api_AbstractApiFactory
{
    public function create(array $params = []): TrackingInterface
    {
        if ($this->config->isNg()) {
            return Mage::getModel('factfinder/api_ng_tracking');
        }

        return Mage::getModel('factfinder/api_tracking');
    }
}
