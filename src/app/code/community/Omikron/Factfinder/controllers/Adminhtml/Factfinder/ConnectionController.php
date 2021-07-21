<?php
include MAGENTO_ROOT . '/vendor/autoload.php';

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron_Factfinder_Model_Api_TestConnectionFactory as TestConnectionFactory;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;

class Omikron_Factfinder_Adminhtml_Factfinder_ConnectionController extends Mage_Adminhtml_Controller_Action
{
    /** @var TestConnectionFactory */
    private $testConnectionFactory;

    /** @var AuthConfig */
    private $authConfig;

    /** @var CommunicationConfig */
    private $communicationConfig;

    /** @var ClientBuilder */
    private $clientBuilder;

    protected function _construct()
    {
        $this->clientBuilder       = new ClientBuilder();
        $this->authConfig          = Mage::getModel('factfinder/config_auth');
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
    }

    public function testAction()
    {
        $message = $this->__('Connection successfully established.');

        try {
            $request = $this->getRequest();
            $clientBuilder = $this->clientBuilder
                ->withCredentials($this->getCredentials($this->getRequest()->getParams()))
                ->withServerUrl($request->getParam('serverUrl'));

            $searchAdapter = (new AdapterFactory($clientBuilder, $request->getParam('version')))->getSearchAdapter();
            $searchAdapter->search($request->getParam('channel'), '*');

            $message = __('Connection successfully established.');
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
            'prefix'  => $params['authenticationPrefix'],
            'postfix' => $params['authenticationPostfix'],
        ];

        $params = $this->extractAuthParams($params);
        return new Credentials(...array_values($params));
    }

    /**
     * @param array $params
     *
     * @return array
     */
    private function extractAuthParams(array $params)
    {
        return array_filter($params, function ($k) {
            return in_array($k, ['username', 'password', 'authenticationPrefix', 'authenticationPostfix']);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/factfinder');
    }
}
