<?php

use Varien_Event_Observer as Event;

class Omikron_Factfinder_Model_Observer_Tracking
{
    public function saveProductToSession(Event $event)
    {
        $item    = $event->getQuoteItem();
        $product = $item->getProduct();


        if ($item->getParentItem()) {
            $item = $item->getParentItem();
        }

        $price = $item->getProduct()->getFinalPrice();

        $session = Mage::getSingleton('core/session');
        $session->setData('ff_add_to_cart_id', $product->getId());
        $session->setData('ff_add_to_cart_sku', $product->getSku());
        $session->setData('ff_add_to_cart_price', $price);
        $session->setData('ff_add_to_cart_qty', $product->getQty());
    }
}
