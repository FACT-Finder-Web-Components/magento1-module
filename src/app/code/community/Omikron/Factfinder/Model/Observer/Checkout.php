<?php

declare(strict_types=1);

use Mage_Sales_Model_Order_Item as OrderItem;
use Omikron_Factfinder_Helper_Product as ProductHelper;
use Omikron_Factfinder_Model_Api_Tracking as Tracking;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Varien_Event_Observer as Event;

class Omikron_Factfinder_Model_Observer_Checkout
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

        $trackingProducts = array_map(
            function (OrderItem $item) {
                return [
                    'id'       => $this->productHelper->getProductNumber($item->getProduct()),
                    'masterId' => $this->productHelper->getMasterProductNumber($item->getProduct()),
                    'price'    => $item->getPrice(),
                    'count'    => $item->getQtyOrdered()
                ];
            }, $cart->getAllVisibleItems()
        );

        $this->tracking->execute('checkout', $trackingProducts);
    }
}
