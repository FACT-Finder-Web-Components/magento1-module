<?php

class Omikron_Factfinder_Adminhtml_Testconnection_TestconnectionController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $store = Mage::app()->getStore();
        $communicationHelper = Mage::helper('factfinder/communication');

        $conCheck = $communicationHelper->updateFieldRoles($store);

        if ($conCheck['success']) {
            $message = (string) $this->__('Success! Connection successfully tested!');
        } else {
            $message = (string) $this->__('Error! Connection could not be established. Please check your setup.');
            if(strlen($conCheck['ff_error_stacktrace'])) {
                $message .= ' ' . $this->__('FACT-Finder error message:') . ' ' . $conCheck['ff_error_stacktrace'];
            }
        }

        $this->getResponse()->setHeader('Content-type', 'application/json');
        $this->getResponse()->setBody(Mage::helper('core')->jsonEncode(['message' => $message]));
    }
}
