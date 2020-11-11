<?php

use Mage_Catalog_Model_Category as Category;
use Varien_Event_Observer as Event;

class Omikron_Factfinder_Model_Observer_Category
{
    const CONFIG_PATH_USE_FOR_CATEGORIES = 'factfinder/general/use_for_categories';

    public function updateLayout(Event $event)
    {
        if ($event->getData('action')->getFullActionName() !== 'catalog_category_view') {
            return;
        }

        /** @var ?Category $category */
        $category = Mage::registry('current_category');
        if ($category && $category->getDisplayMode() !== Category::DM_PAGE && $this->isUsedForCategories()) {
            $event->getData('layout')->getUpdate()->addHandle('factfinder_category_view');
        }
    }

    private function isUsedForCategories()
    {
        $enabled = Mage::getModel('factfinder/config_communication')->isChannelEnabled();
        return $enabled && Mage::getStoreConfigFlag(self::CONFIG_PATH_USE_FOR_CATEGORIES);
    }
}
