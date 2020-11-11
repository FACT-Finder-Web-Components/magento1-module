<?php

class Omikron_Factfinder_Model_SessionData
{
    /** @var Mage_Customer_Model_Session */
    private $customerSession;

    public function __construct()
    {
        $this->customerSession = Mage::getSingleton('customer/session');
    }

    public function getUserId()
    {
        return (int) $this->customerSession->getCustomerId();
    }

    public function getSessionId()
    {
        return $this->getCorrectSessionId((string) $this->customerSession->getSessionId());
    }

    private function getCorrectSessionId($sessionId, $length = 30)
    {
        return substr(md5($sessionId ?: uniqid('', true)), 0, $length);
    }
}
