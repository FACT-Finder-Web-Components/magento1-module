<?php

interface Omikron_Factfinder_Model_Interface_ClientInterface
{
    public function get(string $endpoint, array $params): array;

    public function post(string $endpoint, array $params): array;
}
