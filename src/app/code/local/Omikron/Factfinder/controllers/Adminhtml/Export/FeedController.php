<?php

class Omikron_Factfinder_Adminhtml_Export_FeedController extends Mage_Adminhtml_Controller_Action
{
    /** @var Omikron_Factfinder_Model_Export_Product $product */
    private $product;

    private $store;

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $this->product = Mage::getModel('Omikron_Factfinder_Model_Export_Product');
        $this->store = Mage::app()->getStore();

        parent::__construct($request, $response, $invokeArgs);
    }

    public function indexAction()
    {
        $result = $this->product->exportProduct($this->store);

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(['message' => $result]));
    }

    public function uploadAction()
    {
        $result = $this->product->uploadProduct($this->store);

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(['message' => $result]));
    }
}
