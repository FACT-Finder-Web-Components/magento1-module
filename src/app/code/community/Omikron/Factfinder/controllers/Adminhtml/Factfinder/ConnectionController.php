<?php

use Omikron_Factfinder_Model_Config_AuthConfig as AuthConfig;
use Omikron_Factfinder_Model_Api_Credentials as Credentials;
use Omikron_Factfinder_Model_Api_TestConnection as ApiTestConnection;

class Omikron_Factfinder_Adminhtml_Factfinder_ConnectionController extends Mage_Adminhtml_Controller_Action
{
    /** @var ApiTestConnection */
    private $testConnection;

    /** @var AuthConfig */
    private $authConfig;

    public function _construct( ) {
        $this->testConnection = Mage::getModel('factfinder/api_testconnection');
        $this->authConfig     = Mage::getModel('factfinder/config_auth');
    }

    public function testAction()
    {
        $message = __('Connection successfully established.');

        try {
            $request = $this->getRequest();
            $params  = $this->getCredentials($request->getParams())->toArray() + ['channel' => $request->getParam('channel')];
            $this->testConnection->execute($request->getParam('serverUrl'), $params);
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

    /**
     * @param array $params
     *
     * @return mixed
     */
    private function getCredentials(array $params)
    {
        $params += [
            'prefix'  => $params['authentication_prefix'],
            'postfix' => $params['authentication_postfix'],
        ];

        $params = $this->extractAuthParams($params);
        return new Credentials(...array_values($params));
    }

    /**
     * @param array $params
     * @return array
     */
    private function extractAuthParams(array $params)
    {
        return array_filter($params, function ($k, $i) {return in_array($k, ['username', 'password', 'authenticationPrefix', 'authenticationPostfix']);}, ARRAY_FILTER_USE_KEY);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/factfinder');
    }
}
