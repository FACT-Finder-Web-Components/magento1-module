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
        $sessionId = $sessionId ?: sha1(uniqid('', true));
        $sessionId = str_repeat($sessionId, ceil($length / (strlen($sessionId)) + 1));
        return substr($sessionId, 0, $length);
    }
}
