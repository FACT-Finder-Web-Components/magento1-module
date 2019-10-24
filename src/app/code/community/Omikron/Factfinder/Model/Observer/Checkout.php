<?php

use Mage_Sales_Model_Order_Item as OrderItem;
use Omikron_Factfinder_Model_Tracking_Product as TrackingProduct;
use Varien_Event_Observer as Event;

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

    /**
     * Listens to:
     * - checkout_submit_all_after
     *
     * @param Varien_Event_Observer $event
     */
    public function submitAllAfter(Event $event)
    {
        if (!Mage::helper('factfinder')->isEnabled()) {
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

        $this->tracking->execute('checkout', $trackingProducts);
    }
}
