<?php
include MAGENTO_ROOT . '/vendor/autoload.php';

use Omikron\FactFinder\Communication\Client\ClientBuilder;
use Omikron\FactFinder\Communication\Credentials;
use Omikron\FactFinder\Communication\Resource\AdapterFactory;
use Omikron_Factfinder_Model_Config_Auth as AuthConfig;
use Omikron_Factfinder_Model_Config_Communication as CommunicationConfig;
use Omikron_Factfinder_Model_Api_CredentialsFactory as CredentialsFactory;

class Omikron_Factfinder_Adminhtml_Factfinder_ConnectionController extends Mage_Adminhtml_Controller_Action
{
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
        try {
            $request = $this->getRequest();
            $clientBuilder = $this->clientBuilder
                ->withCredentials(CredentialsFactory::create($this->getRequest()->getParams()))
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

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/factfinder');
    }
}
