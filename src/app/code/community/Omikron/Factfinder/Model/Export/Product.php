<?php

class Omikron_Factfinder_Model_Export_Product
{
    const FEED_PATH          = 'factfinder';
    const FEED_FILE          = 'export.';
    const FEED_FILE_FILETYPE = 'csv';
    const BATCH_SIZE         = 3000;

    /** @var Omikron_Factfinder_Helper_Data */
    protected $dataHelper;

    /** @var Omikron_Factfinder_Helper_Product */
    protected $productHelper;

    /** @var Omikron_Factfinder_Helper_Upload */
    protected $uploadHelper;

    /** @var Omikron_Factfinder_Model_Api_PushImport */
    protected $pushImport;

    /** @var Omikron_Factfinder_Model_Config_Communication */
    protected $communicationConfig;

    public function __construct()
    {
        $this->productHelper       = Mage::helper('factfinder/product');
        $this->uploadHelper        = Mage::helper('factfinder/upload');
        $this->pushImport          = Mage::getModel('factfinder/api_pushImport');
        $this->communicationConfig = Mage::getModel('factfinder/config_communication');
    }

    /**
     * Generate and export all products for a specific store
     *
     * @param Mage_Core_Model_Store $store
     * @param string $filename
     * @return array
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function exportProduct($store, $filename = '')
    {
        if ($filename == '') {
            $filename = $this->communicationConfig->getChannel($store->getId());
        }

        $fullname = self::FEED_FILE . $filename . '.' . self::FEED_FILE_FILETYPE;
        $output   = $this->buildFeed($store);
        $result   = $this->writeFeedToFile($fullname, $output);

        if (isset($result['has_errors']) && $result['has_errors']) {
            return $result;
        }

        $result = $this->uploadFeed($fullname);

        if (isset($result['has_errors']) && $result['has_errors']) {
            return $result;
        }

        $this->pushImport->execute($store->getId());

        return $result;
    }

    /**
     * Build the Product feed for a specific store
     *
     * @param Mage_Core_Model_Store $store
     *
     * @return array
     */
    private function buildFeed($store)
    {
        $output         = [];
        $addHeaderCols  = true;
        $productCount = $this->getFilteredProductCollection($store)->getSize();
        $currentOffset  = 0;

        while ($currentOffset < $productCount) {
            /** @var Mage_Catalog_Model_Resource_Product_Collection $products */
            $products = $this->getProducts($store, $currentOffset);
            foreach ($products as $product) {
                $product->setStoreId($store->getId());
                $rowData = $this->buildFeedRow($product, $store);
                if ($addHeaderCols) {
                    $addHeaderCols = false;
                    $output[]      = array_keys($rowData);
                }
                $output[] = $this->writeLine($rowData);
            }

            $currentOffset += $products->count();
        }

        return $output;
    }

    /**
     * Build a row for the product feed
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     *
     * @return array
     */
    protected function buildFeedRow($product, $store)
    {
        $row = [];
        $attributes = [
            'ProductNumber',
            'MasterProductNumber',
            'Name',
            'Description',
            'Short',
            'ProductUrl',
            'ImageUrl',
            'Price',
            'Manufacturer',
            'Attributes',
            'CategoryPath',
            'Availability',
            'EAN',
            'MagentoEntityId'
        ];

        foreach ($attributes as $attribute) {
            $row[$attribute] = trim(preg_replace('#\s+#', ' ', $this->productHelper->{"get$attribute"}($product, $store)));
        }

        return $row;
    }

    public function exportProductWithExternalUrl($store): array
    {
        $filename = self::FEED_FILE . $this->communicationConfig->getChannel($store->getId()) . '.' . self::FEED_FILE_FILETYPE;
        $output = $this->buildFeed($store);

        return [
            'filename' => $filename,
            'data' => $output
        ];
    }

    /**
     * Get all products for a specific store
     *
     * @param Mage_Core_Model_Store $store
     * @param int $currentOffset
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    private function getProducts($store, $currentOffset)
    {
        $attributesToSelect = array_merge(
            $this->productHelper->getMandatoryAttributes(),
            explode(',', $this->productHelper->getAdditionalAttributes($store)),
            [$this->productHelper->getEANAttributeCode($store)],
            [$this->productHelper->getManufacturerAttributeCode($store)]
        );

        $collection = $this->getFilteredProductCollection($store)->addAttributeToSelect($attributesToSelect);
        $collection->getSelect()->limit(self::BATCH_SIZE, $currentOffset);

        return $collection;
    }

    /**
     * @param $store
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    private function getFilteredProductCollection($store)
    {
        return Mage::getModel('catalog/product')
            ->getCollection()
            ->clear()
            ->addAttributeToFilter('visibility', ['in' => $this->productHelper->getProductVisibility($store)])
            ->addAttributeToFilter('status', Mage_Catalog_Model_Product_Status::STATUS_ENABLED)
            ->addWebsiteFilter($store->getWebsiteId())
            ->setStoreId($store->getId());
    }

    /**
     * Write a line into the product feed
     *
     * @param array $fields
     *
     * @return array
     */
    private function writeLine(array $fields)
    {
        $output = [];
        foreach ($fields as $field) {
            $output[] = $field;
        }

        return $output;
    }

    /**
     * Write the feed output into a file
     *
     * @param string $filename
     * @param array $output
     *
     * @return array
     */
    private function writeFeedToFile($filename, $output)
    {
        $result = [];

        try {
            $io = new Varien_Io_File();
            $path = Mage::getBaseDir() . DS . self::FEED_PATH . DS;
            $io->setAllowCreateFolders(true);
            $io->open(['path' => $path]);
            $io->streamOpen($filename, 'w+');
            $io->streamLock(true);
            foreach ($output as $item) {
                $io->streamWriteCsv($item, ';');
            }
            $io->streamClose();
        } catch (\Exception $e) {
            $result['has_errors'] = true;
            $result['message'] = __('Error: Could not write file') . ' - ' . $e->getMessage();
        }

        return $result;
    }

    /**
     * Upload the specified product feed file to factfinder
     *
     * @param string $filename
     *
     * @return array
     */
    private function uploadFeed($filename)
    {
        $result = [];

        $uploadResult = $this->uploadHelper->upload(Mage::getBaseDir(). DS . self::FEED_PATH . DS . $filename, $filename);

        if ($uploadResult['success']) {
            $result['has_errors'] = false;
            $result['message'] = __('File uploaded successfully!');
        } else {
            $result['has_errors'] = true;
            $result['message'] = $uploadResult['message'];
        }
        return $result;
    }
}
