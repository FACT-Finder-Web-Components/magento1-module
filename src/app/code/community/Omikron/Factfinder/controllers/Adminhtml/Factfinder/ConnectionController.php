<?php

use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Omikron_Factfinder_Model_SdkClient_Client as SdkClient;
use Omikron_Factfinder_Model_SdkClient_Resources_AdapterFactory as AdapterFactory;

class Omikron_Factfinder_Adminhtml_Factfinder_ConnectionController extends Mage_Adminhtml_Controller_Action
{
    /** @var AuthConfig */
    private $authConfig;

    /** @var CommunicationConfig */
    private $communicationConfig;
    /** @var SdkClient */
    protected $sdkClient;
    protected function _construct()
    {
        $this->authConfig            = Mage::getModel('factfinder/config_auth');
        $this->communicationConfig   = Mage::getModel('factfinder/config_communication');
        $this->sdkClient             = Mage::getModel('factfinder/sdkClient_client');
        $this->sdkClient->init($this->authConfig);
    }

    public function testAction()
    {
        $message = $this->__('Connection successfully established.');

        try {
            $connectionAdapter = (new AdapterFactory($this->sdkClient, $this->communicationConfig))->getConnectionAdapter();
            $connectionAdapter->connect();
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return $this->jsonResponse($message);
    }

    private function jsonResponse($message)
    {
        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(['message' => $message]));
    }
}
