<?php

use Mage_Core_Model_App_Area as Area;

class Omikron_Factfinder_Model_Cron
{
    /** @var Omikron_Factfinder_Model_Export_Product */
    protected $productExportModel;

    /** @var Mage_Core_Model_App_Emulation */
    protected $appEmulation;

    public function __construct()
    {
        $this->productExportModel = Mage::getModel('factfinder/export_product');
        $this->appEmulation       = Mage::getSingleton('core/app_emulation');
    }

    public function exportFeed(Mage_Cron_Model_Schedule $schedule) // phpcs:ignore
    {
        if (!Mage::helper('factfinder')->isCronEnabled()) {
            return;
        }

        foreach (Mage::app()->getStores() as $store) {
            $environmentInfo = $this->appEmulation->startEnvironmentEmulation($store->getId(), Area::AREA_ADMINHTML);
            Mage::app()->addEventArea(Area::AREA_ADMINHTML);
            $this->productExportModel->exportProduct($store);
            $this->appEmulation->stopEnvironmentEmulation($environmentInfo);
        }
    }
}
