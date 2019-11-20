<?php

use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Exception_ResponseException as ResponseException;

interface Omikron_Factfinder_Model_Interface_Api_TestConnectionInterface
{
    /**
     * @param string $serverUrl
     * @param array $params
     * @param Credentials $credentials
     *
     * @return bool
     * @throws ResponseException
     */
    public function execute(string $serverUrl, array $params, Credentials $credentials): bool;
}
