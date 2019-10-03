<?php

use Varien_Event_Observer as Event;
use Omikron_Factfinder_Model_Tracking_Product as TrackingProduct;

class Omikron_Factfinder_Model_Observer_Cart
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

    public function addAfter(Event $event)
    {
        /** @var Mage_Catalog_Model_Product $product */
        $product = $event->getProduct();
        /** @var Mage_Sales_Model_Quote_Item $quoteItem */
        $quoteItem = $event->getData('quote_item');

        $this->tracking->execute('cart', [new TrackingProduct(
            $this->productHelper->getProductNumber($product),
            $this->productHelper->getMasterProductNumber($product),
            $product->getFinalPrice(1),
            $quoteItem->getQty())]);
    }
}
