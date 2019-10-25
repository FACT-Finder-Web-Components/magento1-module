<?php

class Omikron_Factfinder_ExportController extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        list($username, $password) = Mage::helper('core/http')->authValidate();
        if (!$this->authenticate($username, $password)) {
            $response = $this->getResponse();
            $response->setHttpResponseCode(401);
            $response->setHeader('WWW-Authenticate', 'Basic realm="Resticted area"');
            $this->setFlag('', self::FLAG_NO_DISPATCH, true);
        }
        return parent::preDispatch();
    }

    private function authenticate($username, $password)
    {
        $ff = Mage::helper('factfinder');
        return strcmp($username, $ff->getUploadUrlUser()) === 0 && strcmp($password, $ff->getUploadUrlPassword()) === 0;
    }

    /**
     * Generate downloadable CSV file
     *
     * @throws Mage_Core_Model_Store_Exception
     */
    public function indexAction()
    {
        $storeId = $this->getRequest()->getParam('store', Mage::app()->getDefaultStoreView());
        $store   = Mage::app()->getStore($storeId);
        $data    = Mage::getSingleton('factfinder/export_product')->exportProductWithExternalUrl($store);

        $response = $this->getResponse();
        $response->setHeader('Content-Type', 'text/csv; charset=utf-8');
        $response->setHeader('Content-Disposition', 'attachment; filename=' . $data['filename']);
        $response->setBody($this->toCsv($data['data']));
    }

    private function toCsv(array $data)
    {
        ob_start();
        $csv = new Varien_File_Csv();
        $csv->setDelimiter(';');
        $csv->setEnclosure('"');
        $csv->saveData('php://output', $data);
        return ob_get_clean();
    }
}
