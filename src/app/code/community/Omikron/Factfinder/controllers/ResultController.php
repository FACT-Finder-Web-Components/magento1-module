<?php

use Mage_Page_Block_Html_Breadcrumbs as Breadcrumbs;

class Omikron_Factfinder_ResultController extends Mage_Core_Controller_Front_Action
{
    /** @var string */
    protected $_realModuleName = 'Mage_CatalogSearch';

    public function indexAction()
    {
        $this->loadLayout();

        $title = $this->__("Search results for: '%s'", $this->getQueryText());
        $this->_title()->_title($title);

        $breadcrumbs = $this->getLayout()->getBlock('breadcrumbs');
        if ($breadcrumbs instanceof Breadcrumbs) {
            $breadcrumbs->addCrumb('home', [
                'label' => $this->__('Home'),
                'title' => $this->__('Go to Home Page'),
                'link'  => Mage::getBaseUrl(),
            ]);
            $breadcrumbs->addCrumb('search', ['label' => $title, 'title' => $title]);
        }

        $this->renderLayout();
    }

    private function getQueryText()
    {
        return (string) $this->getRequest()->getParam('query');
    }
}
