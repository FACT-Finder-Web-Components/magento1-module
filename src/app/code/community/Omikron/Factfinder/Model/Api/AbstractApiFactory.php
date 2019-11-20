<?php

declare(strict_types=1);

abstract class Omikron_Factfinder_Model_Api_AbstractApiFactory
{
    /** @var Omikron_Factfinder_Model_Config_Communication */
    protected $config;

    public function __construct()
    {
        $this->config = Mage::getModel('factfinder/config_communication');
    }

    public abstract function create(array $params = []);
}
