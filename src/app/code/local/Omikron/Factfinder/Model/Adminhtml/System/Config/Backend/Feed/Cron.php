<?php

/**
 * Class Omikron_Factfinder_Model_Adminhtml_System_Config_Backend_Feed_Cron
 */
class Omikron_Factfinder_Model_Adminhtml_System_Config_Backend_Feed_Cron extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH = 'crontab/jobs/factfinder_feed_export/schedule/cron_expr';

    /**
     * @return Mage_Core_Model_Abstract|void
     * @throws Exception
     */
    protected function _afterSave()
    {
        $time = $this->getData('groups/configurable_cron/fields/time/value');
        $frequency = $this->getValue();

        $frequencyWeekly = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_WEEKLY;
        $frequencyMonthly = Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency::CRON_MONTHLY;

        $cronExprArray = array(
            intval($time[1]),
            intval($time[0]),
            ($frequency == $frequencyMonthly) ? '1' : '*',
            '*',
            ($frequency == $frequencyWeekly) ? '1' : '*',
        );
        $cronExprString = join(' ', $cronExprArray);

        try {
            Mage::getModel('core/config_data')
                ->load(self::CRON_STRING_PATH, 'path')
                ->setValue($cronExprString)
                ->setPath(self::CRON_STRING_PATH)
                ->save();
        } catch (Exception $e) {
            throw new Exception(Mage::helper('cron')->__('Unable to save the cron expression.'));
        }
    }
}
