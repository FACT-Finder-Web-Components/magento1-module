<?php

class Omikron_Factfinder_Model_Source_Visibility
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return array_slice(Mage::getSingleton('catalog/product_visibility')->getAllOptions(), 1);
    }
}
