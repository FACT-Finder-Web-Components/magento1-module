<?php

/**
 * Class Omikron_Factfinder_Model_Source_Visibility
 */
class Omikron_Factfinder_Model_Source_Visibility
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        $values  = Mage_Catalog_Model_Product_Visibility::getOptionArray();
        $options = [];

        foreach ($values as $value => $label){
            $options[] = [
                'value' => $value,
                'label' => $label
            ];
        }

        return $options;
    }
}
