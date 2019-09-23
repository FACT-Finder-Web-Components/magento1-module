<?php

class Omikron_Factfinder_Block_Communication extends Mage_Core_Block_Template
{
    private $mergeableParams = ['add-params', 'add-tracking-params', 'keep-url-params', 'parameter-whitelist'];

    private $helper;

    public function __construct()
    {
        $this->helper = Mage::helper('factfinder');
    }

    public function getMergeableParams()
    {
        $params = array_combine($this->mergeableParams,array_map(function ($parameter) {
            return implode(',', array_filter([$this->getData($parameter), $this->getConfigStoredParam($parameter)]));
        }, $this->mergeableParams));

        return array_filter($params, 'boolval');
    }

    private function getConfigStoredParam($paramName)
    {
        return Mage::getStoreConfig('factfinder/advanced/' . str_replace('-','_', $paramName));
    }
}
