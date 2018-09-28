<?php

class Omikron_Factfinder_Block_Adminhtml_System_Config_Button_Upload extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /*
    * Set template
    */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('factfinder/system/config/button/upload.phtml');
    }

    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    /**
     * Return ajax url for button
     *
     * @return string
     */
    public function getAjaxCheckUrl()
    {
        return Mage::helper('adminhtml')->getUrl('factfinder/adminhtml_export_feed/upload');
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'id' => 'omikron_factfinder_feed_upload_button',
                'label' => $this->helper('adminhtml')->__('Upload export files(s) now'),
                'onclick' => 'javascript:uploadCsv(); return false;'
            ));

        return $button->toHtml();
    }
}
