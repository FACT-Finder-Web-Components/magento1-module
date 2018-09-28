<?php

class Omikron_Factfinder_Model_Source_Attribute
{
    /**
     * Options getter     *
     * @return array
     */
    public function toOptionArray()
    {
        $attributes = Mage::getResourceModel('catalog/product_attribute_collection')
            ->getItems();

        foreach ($attributes as $attribute){
            $options[] = [
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getAttributeCode()
            ];
        }

        asort($options);

        return $options;
    }
}
