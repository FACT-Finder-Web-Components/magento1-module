<?php

/**
 * Class Omikron_Factfinder_Adminhtml_Testconnection_TestconnectionController
 */
class Omikron_Factfinder_Adminhtml_Testconnection_TestconnectionController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @var Omikron_Factfinder_Helper_Communication
     */
    protected $connectionHelper;

    /**
     * Internal constructor
     */
    public function _construct()
    {
        $this->connectionHelper =  Mage::helper('factfinder/communication');
    }

    /**
     * @throws Mage_Core_Exception
     * @throws Mage_Core_Model_Store_Exception
     */
    public function indexAction()
    {
        $authData = $this->getAuthFromRequest();
        Mage::register('ff-auth', $authData, true);

        $store = Mage::app()->getStore();
        $conCheck = $this->connectionHelper->checkConnection($store);

        if ($conCheck['success']) {
            $message = (string)$this->__('Success! Connection successfully tested!');
        } else {
            $message = (string)$this->__('Error! Connection could not be established. Please check your setup.');
            if (strlen($conCheck['ff_error_stacktrace'])) {
                $message .= ' ' . $this->__('FACT-Finder error message:') . ' ' . $conCheck['ff_error_stacktrace'];
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(['message' => $message]));
    }

    /**
     * @return array
     */
    private function getAuthFromRequest()
    {
        /** @var Mage_Core_Controller_Request_Http $request */
        $request = $this->getRequest();

        return [
            'serverUrl'             => $request->getPost('serverUrl'),
            'channel'               => $request->getPost('channel'),
            'password'              => md5($request->getPost('password')),
            'username'              => $request->getPost('username'),
            'authenticationPrefix'  => $request->getPost('authenticationPrefix'),
            'authenticationPostfix' => $request->getPost('authenticationPostfix'),
        ];
    }
}
