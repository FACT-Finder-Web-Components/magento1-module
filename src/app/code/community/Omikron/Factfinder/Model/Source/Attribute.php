<?php

use Mage_Eav_Model_Entity_Attribute as Attribute;

class Omikron_Factfinder_Model_Source_Attribute
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = array_map(function (Attribute $item) {
            return ['value' => $item->getAttributeCode(), 'label' => $item->getAttributeCode()];
        }, Mage::getResourceModel('catalog/product_attribute_collection')->getItems());

        usort($options, function (array $a, array $b) {
            return strcasecmp($a['value'], $b['value']);
        });

        return $options;
    }
}
