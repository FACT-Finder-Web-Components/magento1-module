<?php

use Mage_Sales_Model_Order_Item as OrderItem;
use Omikron_Factfinder_Helper_Product as ProductHelper;
use Omikron_Factfinder_Model_Api_Tracking_Product as TrackingProduct;
use Omikron_Factfinder_Model_Api_TrackingFactory as TrackingFactory;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Varien_Event_Observer as Event;

class Omikron_Factfinder_Model_Observer_Checkout
{
    /** @var TrackingFactory */
    private $trackingFactory;

    /** @var ProductHelper */
    private $productHelper;

    /** @var CommunicationConfig */
    private $config;

    public function __construct()
    {
        $this->trackingFactory  = Mage::getModel('factfinder/api_trackingFactory');
        $this->productHelper    = Mage::helper('factfinder/product');
        $this->config           = Mage::getModel('factfinder/config_communication');
    }

    /**
     * Listens to:
     * - checkout_submit_all_after
     *
     * @param Varien_Event_Observer $event
     */
    public function submitAllAfter(Event $event)
    {
        if (!$this->config->isChannelEnabled()) {
            return;
        }

        /** @var Mage_Sales_Model_Order $cart */
        $cart = $event->getData('order');

        $trackingProducts = array_map(function (OrderItem $item) {
            return new TrackingProduct(
                $this->productHelper->getProductNumber($item->getProduct()),
                $this->productHelper->getMasterProductNumber($item->getProduct()),
                $item->getPrice(),
                $item->getQtyOrdered());
        }, $cart->getAllVisibleItems());

        $this->trackingFactory->create()->execute('checkout', $trackingProducts);
    }
}
