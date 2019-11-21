<?php

declare(strict_types=1);

class Omikron_Factfinder_Model_Http_Adapter_Curl  extends Varien_Http_Adapter_Curl
{
    public function read()
    {
        return str_replace('HTTP/2 ', 'HTTP/1.1 ', parent::read());
    }
}
