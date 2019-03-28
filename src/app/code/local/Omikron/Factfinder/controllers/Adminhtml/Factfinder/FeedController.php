<?php

use Mage_Core_Controller_Response_Http as Response;

class Omikron_Factfinder_Adminhtml_Factfinder_FeedController extends Mage_Adminhtml_Controller_Action
{
    public function exportAction()
    {
        preg_match('@/store/([a-z]+)/@', $this->_getRefererUrl(), $match);
        $storeId = $match[1] ? $match[1] : Mage::app()->getDefaultStoreView()->getId();
        $export  = Mage::getSingleton('factfinder/export_product');
        $this->jsonResponse($export->exportProduct(Mage::app()->getStore($storeId)), $this->getResponse());
    }

    public function uploadAction()
    {
        $export = Mage::getSingleton('factfinder/export_product');
        $this->jsonResponse($export->uploadProduct(Mage::app()->getStore()), $this->getResponse());
    }

    protected function jsonResponse($message, Response $response)
    {
        $response->setHeader('Content-type', 'application/json');
        $response->setBody(Mage::helper('core')->jsonEncode(['message' => $message]));
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/factfinder');
    }
}
