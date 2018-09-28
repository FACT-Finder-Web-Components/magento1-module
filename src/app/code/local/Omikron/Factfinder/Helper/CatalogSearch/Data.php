<?php

class Omikron_Factfinder_Helper_CatalogSearch_Data extends Mage_CatalogSearch_Helper_Data
{
    /**
     * Query variable name
     */
    const QUERY_VAR_NAME = 'query';

    /**
     * Retrieve search query parameter name
     *
     * @return string
     */
    public function getQueryParamName()
    {
        return self::QUERY_VAR_NAME;
    }

}
