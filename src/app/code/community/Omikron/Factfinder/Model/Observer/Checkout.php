<?php

use Varien_Event_Observer as Event;
use Mage_Sales_Model_Order_Item as OrderItem;
use Omikron_Factfinder_Model_Tracking_Product as TrackingProduct;

class Omikron_Factfinder_Model_Observer_Checkout
{
    /** @var Omikron_Factfinder_Model_Tracking */
    private $tracking;

    /** @var Omikron_Factfinder_Helper_Product */
    private $productHelper;

    public function __construct()
    {
        $this->tracking      = Mage::getModel('factfinder/tracking');
        $this->productHelper = Mage::helper('factfinder/product');
    }

    public function submitAllAfter(Event $event)
    {
        /** @var Mage_Sales_Model_Quote $cart */
        $cart = $event->getData('quote');

        $trackingProducts = array_map(
            function (OrderItem $item) {
                return new TrackingProduct(
                    $this->productHelper->getProductNumber($item->getProduct()),
                    $this->productHelper->getMasterProductNumber($item->getProduct()),
                    $item->getPrice(),
                    $item->getQty()
                );
            }, $cart->getAllVisibleItems()
        );

        $this->tracking->execute('checkout', $trackingProducts);
    }
}
