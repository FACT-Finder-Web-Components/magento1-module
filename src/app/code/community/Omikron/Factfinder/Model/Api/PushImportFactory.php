<?php

declare(strict_types=1);

use Omikron_Factfinder_Model_Interface_Api_PushImportInterface as PushImportInterface;

class Omikron_Factfinder_Model_Api_PushImportFactory extends Omikron_Factfinder_Model_Api_AbstractApiFactory
{
    public function create(array $params = []): PushImportInterface
    {
        if ($this->config->isNg()) {
            return Mage::getModel('factfinder/api_ng_pushImport');
        }
        return Mage::getModel('factfinder/api_pushImport');
    }
}
