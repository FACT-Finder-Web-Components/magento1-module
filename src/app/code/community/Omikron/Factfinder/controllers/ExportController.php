<?php

class Omikron_Factfinder_ExportController extends Mage_Core_Controller_Front_Action
{
    const REALM = 'Restricted area';

    public function indexAction()
    {
        /** @var Omikron_Factfinder_Helper_Data $helper */
        $helper                 = Mage::helper('factfinder/data');
        $validPasswords         = [$helper->getUploadUrlUser() => $helper->getUploadUrlPassword()];
        $validUsers             = array_keys($validPasswords);
        $hasSuppliedCredentials = !(empty($_SERVER['PHP_AUTH_USER']) && empty($_SERVER['PHP_AUTH_PW']));

        $validated = ($hasSuppliedCredentials && in_array($_SERVER['PHP_AUTH_USER'], $validUsers)) &&
            strcmp($_SERVER['PHP_AUTH_PW'], $validPasswords[$_SERVER['PHP_AUTH_USER']]) === 0;

        if (!$validated) {
            header('WWW-Authenticate: Basic realm="' . self::REALM . '"');
            header('HTTP/1.0 401 Unauthorized');
            die($this->__('Not authorized.'));
        }

        try {
            $this->generateCsvFile();
        } catch (\Exception $e) {
            die($this->__('Error: ') . $e->getMessage());
        }
    }

    /**
     * Generate downloadable CSV file
     *
     * @return void
     * @throws Mage_Core_Model_Store_Exception
     */
    private function generateCsvFile()
    {
        $storeId = $this->getRequest()->getParam('store', Mage::app()->getDefaultStoreView());
        $store   = Mage::app()->getStore($storeId);

        /** @var Omikron_Factfinder_Model_Export_Product $product */
        $product = Mage::getModel('Omikron_Factfinder_Model_Export_Product');

        $data = $product->exportProductWithExternalUrl($store);

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $data['filename']);
        $output = fopen('php://output', 'w');

        foreach ($data['data'] as $row) {
            fputcsv($output, $row, ';');
        }
    }
}
