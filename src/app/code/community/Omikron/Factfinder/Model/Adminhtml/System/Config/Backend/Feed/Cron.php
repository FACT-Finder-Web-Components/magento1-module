<?php

use Mage_Adminhtml_Model_System_Config_Source_Cron_Frequency as CronFrequency;

class Omikron_Factfinder_Model_Adminhtml_System_Config_Backend_Feed_Cron extends Mage_Core_Model_Config_Data
{
    const CRON_STRING_PATH = 'crontab/jobs/factfinder_feed_export/schedule/cron_expr';

    protected function _afterSave()
    {
        list($hour, $minute) = $this->getData('groups/configurable_cron/fields/time/value');
        $frequency = $this->getValue();

        Mage::app()->getConfig()->saveConfig(self::CRON_STRING_PATH, implode(' ', [
            (int) $minute,
            (int) $hour,
            $frequency === CronFrequency::CRON_MONTHLY ? '1' : '*',
            '*',
            $frequency === CronFrequency::CRON_WEEKLY ? '1' : '*',
        ]));
    }
}
