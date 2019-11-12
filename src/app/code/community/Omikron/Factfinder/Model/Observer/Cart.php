<?php

use Omikron_Factfinder_Model_Tracking_Product as TrackingProduct;
use Varien_Event_Observer as Event;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Model_Observer_Cart
{
    /** @var Omikron_Factfinder_Model_Tracking */
    private $tracking;

    /** @var Omikron_Factfinder_Helper_Product */
    private $productHelper;

    /** @var CommunicationConfig */
    private $config;

    public function __construct()
    {
        $this->tracking      = Mage::getModel('factfinder/api_tracking');
        $this->productHelper = Mage::helper('factfinder/product');
        $this->config        = Mage::getModel('factfinder/config_communication');
    }

    /**
     * Listens to:
     * - checkout_cart_product_add_after
     *
     * @param Varien_Event_Observer $event
     */
    public function addAfter(Event $event)
    {
        if (!$this->config->isChannelEnabled()) {
            return;
        }

        /** @var Mage_Catalog_Model_Product $product */
        $product = $event->getData('product');
        /** @var Mage_Sales_Model_Quote_Item $quoteItem */
        $quoteItem = $event->getData('quote_item');

        $trackingProduct = new TrackingProduct(
            $this->productHelper->getProductNumber($product),
            $this->productHelper->getMasterProductNumber($product),
            $product->getFinalPrice(1),
            $quoteItem->getQty());

        $this->tracking->execute('cart', [$trackingProduct]);
    }
}
