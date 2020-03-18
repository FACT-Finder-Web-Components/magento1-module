<?php

use Mage_Eav_Model_Entity_Attribute as Attribute;

class Omikron_Factfinder_Model_Source_ImportTypes
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => 'data',
                'label' => 'Data'
            ],
            [
                'value' => 'suggest',
                'label' => 'Suggest'
            ],
            [
                'value' => 'recommendation',
                'label' => 'Recommendation'
            ],
        ];
    }
}
