<?php

class Omikron_Factfinder_Model_Authentication_Password extends Mage_Core_Model_Config_Data
{
    /**
     * Encrypt value before saving
     *
     */
    public function _beforeSave()
    {
        $value = md5($this->getValue());

        if (preg_match('/^[a-f0-9]{32}$/i', $this->getValue())) {
            $value = $this->getOldValue();
        }

        $this->setValue($value);
    }

    /**
     * Get & decrypt old value from configuration
     *
     * @return string
     */
    public function getOldValue()
    {
        return parent::getOldValue();
    }
}
