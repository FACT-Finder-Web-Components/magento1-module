<?php

/**
 * Class Omikron_Factfinder_Model_Cron
 */
class Omikron_Factfinder_Model_Cron
{
    /**
     * @var Omikron_Factfinder_Model_Export_Product
     */
    protected $productExportModel;

    /**
     * @var Omikron_Factfinder_Helper_Data
     */
    protected $configHelper;

    /**
     * Omikron_Factfinder_Model_Cron constructor.
     */
    public function __construct()
    {
        $this->productExportModel = Mage::getModel('factfinder/export_product');
        $this->configHelper = Mage::helper('factfinder/data');
    }

    /**
     * @param Mage_Cron_Model_Schedule $schedule
     */
    public function exportFeed(Mage_Cron_Model_Schedule $schedule)
    {
        if ($this->configHelper->isCronEnabled()) {
            foreach (Mage::app()->getStores() as $store) {
                $this->productExportModel->exportProduct($store);
            }
        }
    }
}