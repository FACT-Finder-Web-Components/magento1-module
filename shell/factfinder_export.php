<?php
require_once 'abstract.php';

/**
 * Class Mage_Shell_FactFinder
 */
class Mage_Shell_FactFinder extends Mage_Shell_Abstract
{
    const STORE_ID_ARG  = 'store';
    const FILE_NAME_ARG = 'fileName';

    /**
     * @return void
     */
    public function run()
    {
        if ($this->getArg(self::FILE_NAME_ARG) && $this->getArg(self::STORE_ID_ARG)) {
            $this->exportStore($this->getArg(self::STORE_ID_ARG), $this->getArg(self::FILE_NAME_ARG));
        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     * @return string
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php factfinder_feed_export.php -- [options]

  --store <storeId>             Export Product CSV for store
  --fileName <fileName>         Set a exported file name

  <storeId>     Id of the store you want to export
  <filename>    Generated file name

USAGE;
    }

    /**
     * Export products for store
     *
     * @param int $storeId
     * @param string $filename
     *
     * @return void
     */
    private function exportStore($storeId, $filename)
    {
        /** @var Mage_Core_Model_App_Emulation $appEmulation */
        $appEmulation = Mage::getSingleton('core/app_emulation');
        $initialEnvironmentInfo = $appEmulation->startEnvironmentEmulation($storeId, Mage_Core_Model_App_Area::AREA_ADMINHTML);
        Mage::app()->addEventArea(Mage_Core_Model_App_Area::AREA_ADMINHTML);
        $store = Mage::getModel('core/store')->load($storeId);
        /** @var Omikron_Factfinder_Model_Export_Product $exporter */
        $exporter = Mage::getModel('Omikron_Factfinder_Model_Export_Product');
        $exporter->exportProduct($store, $filename);
        $appEmulation->stopEnvironmentEmulation($initialEnvironmentInfo);

        printf("Successfully generated export to: %s\n", $filename);
    }
}

$shell = new Mage_Shell_FactFinder();
$shell->run();
