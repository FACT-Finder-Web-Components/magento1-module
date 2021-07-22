<?php

declare(strict_types=1);

use Omikron_Factfinder_Helper_Product as ProductHelper;
use Omikron_Factfinder_Model_Api_Tracking as Tracking;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Varien_Event_Observer as Event;

class Omikron_Factfinder_Model_Observer_Cart
{
    /** @var Tracking */
    private $tracking;

    /** @var ProductHelper */
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

        $this->tracking->execute(
            'cart', [
                [
                    'id'       => $this->productHelper->getProductNumber($product),
                    'masterId' => $this->productHelper->getMasterProductNumber($product),
                    'price'    => $product->getFinalPrice(1),
                    'count'      => $quoteItem->getQty()
                ]
            ]
        );
    }
}
