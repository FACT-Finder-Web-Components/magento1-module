<?php

use Omikron_Factfinder_Exception_ResponseException as ResponseException;

interface Omikron_Factfinder_Model_Interface_Api_PushImportInterface
{
    /**
     * @param null|int $scopeId
     * @param array    $params
     *
     * @return bool
     * @throws ResponseException
     */
    public function execute(int $scopeId = null, array $params = []): bool;
}
