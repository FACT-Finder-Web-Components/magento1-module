<?php

class Omikron_Factfinder_Model_Source_CustomerGroup
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return Mage::getResourceModel('customer/group_collection')->toOptionArray();
    }
}
