<?php

/**
 * Class Omikron_Factfinder_Helper_Product_Price
 */
class Omikron_Factfinder_Helper_Product_Price
{
    /**
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @param array $customerGroups
     * @param callable $callback
     * @return array
     *
     * @throws Mage_Core_Exception
     */
    public function collectPrices($product, $store, $customerGroups, $callback)
    {
        $prices =[];

        if (!is_callable($callback)) {
            throw new \http\Exception\InvalidArgumentException(__('Price calculation callback is not callable'));
        }

        foreach ($customerGroups as $group) {
            $this->prepareStateForCatalogRuleProcessing($product);
            $this->registerCatalogRuleData($group, $store);
            $prices[$group] = $callback($product, $group);
        }

        return $prices;
    }

    /**
     * @param int $customerGroup
     * @param Mage_Core_Model_Store $store
     * @throws Mage_Core_Exception
     */
    private function registerCatalogRuleData($customerGroup, $store)
    {
        Mage::register('rule_data', new Varien_Object(array(
            'store_id'  => $store->getId(),
            'website_id'  => $store->getWebsiteId(),
            'customer_group_id' => $customerGroup,
        )),true);
    }

    /**
     * @param Mage_Catalog_Model_Product $product
     */
    private function prepareStateForCatalogRuleProcessing($product)
    {
        Mage::unregister('rule_data');
        $product->unsetData('final_price');
    }
}