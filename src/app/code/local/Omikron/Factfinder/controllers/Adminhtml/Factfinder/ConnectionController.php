<?php

class Omikron_Factfinder_Adminhtml_Factfinder_ConnectionController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @throws Mage_Core_Exception
     * @throws Mage_Core_Model_Store_Exception
     */
    public function testAction()
    {
        Mage::register('ff-auth', $this->getAuthFromRequest(), true);
        $connCheck = Mage::helper('factfinder/communication')->checkConnection(Mage::app()->getStore());

        if ($connCheck['success']) {
            $message = (string) $this->__('Success! Connection successfully tested!');
        } else {
            $message = (string) $this->__('Error! Connection could not be established. Please check your setup.');
            if (strlen($connCheck['ff_error_stacktrace'])) {
                $message .= ' ' . $this->__('FACT-Finder error message: %s', $connCheck['ff_error_stacktrace']);
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

    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/config/factfinder');
    }
}
