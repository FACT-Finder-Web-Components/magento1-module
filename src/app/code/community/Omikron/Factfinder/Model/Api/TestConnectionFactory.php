<?php

declare(strict_types=1);

use Omikron_Factfinder_Model_Interface_Api_TestConnectionInterface as TestConnectionInterface;

class Omikron_Factfinder_Model_Api_TestConnectionFactory extends Omikron_Factfinder_Model_Api_AbstractApiFactory
{
    public function create(array $params = []): TestConnectionInterface
    {
        if (($params['version'] ?? '7.3') === 'ng') {
            return Mage::getModel('factfinder/api_ng_testConnection');
        }

        return Mage::getModel('factfinder/api_testConnection');
    }
}
