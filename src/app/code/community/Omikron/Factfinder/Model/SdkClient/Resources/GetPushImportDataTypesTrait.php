<?php

trait Omikron_Factfinder_Model_SdkClient_Resources_GetPushImportDataTypesTrait
{
    public function getPushImportDataTypes(int $scopeId = null): array
    {
        $dataTypes  = Mage::getStoreConfig('factfinder/data_transfer/ff_push_import_types', $scopeId);
        return explode(',', str_replace('data', 'search', $dataTypes));
    }
}