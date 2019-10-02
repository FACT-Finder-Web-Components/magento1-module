<?php

class Omikron_Factfinder_Adminhtml_Factfinder_ConnectionController extends Mage_Adminhtml_Controller_Action
{
    public function testAction()
    {
        $message = $this->__('Success! Connection successfully tested!');

        try {
            Mage::register('ff-auth', $this->getAuthFromRequest(), true);
            Mage::helper('factfinder/communication')->checkConnection(Mage::app()->getStore());
        } catch (Exception $e) {
            $message = $this->__('Connection could not be established: %s', $e->getMessage());
        }

        $this->jsonResponse($message);
    }

    private function jsonResponse($message)
    {
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
