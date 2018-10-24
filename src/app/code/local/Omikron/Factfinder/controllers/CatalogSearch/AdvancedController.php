<?php
require_once  Mage::getModuleDir('controllers', 'Mage_CatalogSearch'). DS .'AdvancedController.php';

/**
 * Class Omikron_Factfinder_CatalogSearch_AdvancedController
 */
class Omikron_Factfinder_CatalogSearch_AdvancedController extends Mage_CatalogSearch_AdvancedController
{
    /**
     * Disable core advanced catalogsearch
     */
    public function indexAction()
    {
        $this->_redirect('/');
    }
}
