<?php

use Mage_Core_Model_App_Area as Area;

class Omikron_Factfinder_Helper_Product extends Mage_Core_Helper_Abstract
{
    // attributes properties
    const ATTRIBUTE_LIMIT = 1000;
    const ATTRIBUTE_DELIMITER = '|';

    // data transfer
    const PATH_FF_MANUFACTURER = 'factfinder/data_transfer/ff_manufacturer';
    const PATH_FF_EAN = 'factfinder/data_transfer/ff_ean';
    const PATH_FF_ADDITIONAL_ATTRIBUTES = 'factfinder/data_transfer/ff_additional_attributes';
    const PATH_FF_PRODUCT_VISIBILITY = 'factfinder/data_transfer/ff_product_visibility';
    const PATH_FF_PRICE_CUSTOMER_GROUPS = 'factfinder/data_transfer/ff_price_customer_group';

    // images
    const PATH_FF_IMAGE_RESIZE_WIDTH  = 'factfinder/data_transfer/ff_image_resize_width';
    const PATH_FF_IMAGE_RESIZE_HEIGHT = 'factfinder/data_transfer/ff_image_resize_height';


    /**
     * Categories in memory
     *
     * @var array
     */
    protected $categories = [];

    /**
     * Attributes in memory
     *
     * @var array
     */
    protected $attributes = [];

    /**
     * Configurable products in memory
     *
     * @var array
     */
    protected $configurableProducts = [];

    /**
     * @var Mage_Catalog_Model_Product_Type_Configurable
     */
    protected $configurableProductModel;

    /**
     * @var Mage_Catalog_Helper_Image
     */
    protected $imageHelper;

    /**
     * @var Omikron_Factfinder_Helper_Product_Price
     */
    protected $priceHelper;

    /**
     * Omikron_Factfinder_Helper_Product constructor.
     */
    public function __construct()
    {
        $this->configurableProductModel = Mage::getModel('catalog/product_type_configurable');
        $this->imageHelper              = Mage::helper('catalog/image');
        $this->priceHelper              = Mage::helper('factfinder/product_price');
    }

    /**
     * Get the product number
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getProductNumber($product)
    {
        return $product->getData('sku');
    }

    /**
     * Get the master product number
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getMasterProductNumber($product)
    {
        if ($product->getTypeId() == Mage_Catalog_Model_Product_Type_Configurable::TYPE_CODE) {
            /**
             * It unnecessary to check if configurable product has parent because it's impossible
             */
            return $product->getSku();
        }

        $masterProductNumber = null;

        if ($parentId = $this->getProductParentIdByProductId($product->getId())) {
            if (isset($this->configurableProducts[$parentId])) {
                $parentProduct = $this->configurableProducts[$parentId];
            } else {
                $parentProduct = Mage::getModel('catalog/product')->load($parentId);
            }

            if ($parentProduct->getId()) {
                $this->configurableProducts[$parentId] = $parentProduct;
                $masterProductNumber = $parentProduct->getSku();
            }
        }

        return $masterProductNumber ? $masterProductNumber : $product->getSku();
    }

    /**
     * Get the product name
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getName($product)
    {
        return $product->getData('name');
    }

    /**
     * Get the product description
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getDescription($product)
    {
        return $this->cleanValue($product->getData('description'));
    }

    /**
     * Get the product short description
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    public function getShort($product)
    {
        return $this->cleanValue($product->getData('short_description'));
    }

    /**
     * Get the product detail page url
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store      $store
     * @return string
     */
    public function getProductUrl($product, $store)
    {
        return $product->getUrlInStore(['_store' => $store->getCode()]);
    }

    /**
     * Retrieve product thumbnail URL
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store      $store
     * @param string                     $attributeName
     *
     * @return string
     */
    public function getImageUrl($product, $store, $attributeName = 'image')
    {
        $width  = (int) Mage::getStoreConfig(self::PATH_FF_IMAGE_RESIZE_WIDTH, $store->getId());
        $height = (int) Mage::getStoreConfig(self::PATH_FF_IMAGE_RESIZE_HEIGHT, $store->getId());

        try {
            $imgSrc = (string) Mage::helper('catalog/image')->init($product, $attributeName)
                ->constrainOnly(true)
                ->keepAspectRatio(true)
                ->keepTransparency(true)
                ->resize($width, $height);
        } catch (Exception $e) {
            $imgSrc = Mage::getDesign()->getSkinUrl(Mage::helper('catalog/image')->getPlaceholder(), [
                '_area'  => Area::AREA_FRONTEND,
                '_store' => $store,
            ]);
        }

        return $imgSrc;
    }

    /**
     * Get the product price
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Core $store
     * @return string
     */
    public function getPrice($product, $store)
    {
        $customerGroups = $this->getCustomerGroupsForPriceExport($store);
        if (count($customerGroups) > 1) {
            $callback = function ($product, $group) {
                return $this->cleanValue($group,true ) . '=' . number_format($product->getFinalPrice(), 2, '.', '');
            };
            $prices = $this->priceHelper->collectPrices($product, $store, $customerGroups, $callback);

            return self::ATTRIBUTE_DELIMITER . implode(self::ATTRIBUTE_DELIMITER, $prices) . self::ATTRIBUTE_DELIMITER;

        } else {
            $callback = function ($product) {
                return number_format($product->getFinalPrice(), 2, '.', '');
            };
            $price = $this->priceHelper->collectPrices($product, $store, $customerGroups, $callback);

            return reset($price);
        }
    }

    /**
     * Get the product category
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    public function getCategoryPath($product, $store)
    {
        $categoryIds = $product->getCategoryIds();
        $path = [];
        $attrCount = 0;

        foreach ($categoryIds as $categoryId) {
            if (isset($this->categories[$categoryId])) {
                /** @var Mage_Catalog_Model_Category $category */
                $category = $this->categories[$categoryId];
            } else {
                /** @var Mage_Catalog_Model_Category $category */
                $category = Mage::getModel('catalog/category')->load($categoryId);
                $this->categories[$categoryId] = $category;
            }

            if ($attrCount < self::ATTRIBUTE_LIMIT && $category->getIsActive()) {
                $categoryPath = $this->getCategoryPathByCategory($category, $store);
                if (!empty($categoryPath)) {
                    $path[] = $categoryPath;
                    $attrCount++;
                }
            }
        }

        return implode(self::ATTRIBUTE_DELIMITER, $path);
    }

    /**
     * Get if the product is available
     *
     * @param Mage_Catalog_Model_Product $product
     * @return int
     */
    public function getAvailability($product)
    {
        return (int) $product->isAvailable();
    }

    /**
     * Get the magento product entity id
     *
     * @param Mage_Catalog_Model_Product $product
     * @return int
     */
    public function getMagentoEntityId($product)
    {
        return (int) $product->getId();
    }

    /**
     * Get if the product manufacturer
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return mixed
     */
    public function getManufacturer($product, $store)
    {
        return $this->getConfiguredAttributeValue($product, $this->getManufacturerAttributeCode($store));
    }

    /**
     * Get if the product EAN
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return mixed
     */
    public function getEAN($product, $store)
    {
        return $this->getConfiguredAttributeValue($product, $this->getEANAttributeCode($store));
    }

    /**
     * @param $store
     *
     * @return string|null
     */
    public function getEANAttributeCode($store)
    {
        return Mage::getStoreConfig(self::PATH_FF_EAN, $store->getId());
    }

    /**
     * @param $store
     *
     * @return string|null
     */
    public function getManufacturerAttributeCode($store)
    {
        return Mage::getStoreConfig(self::PATH_FF_MANUFACTURER, $store->getId());
    }
    /**
     * @return array
     */
    public function getMandatoryAttributes()
    {
        return [
            'name',
            'description',
            'short_description',
            'url_key',
            'small_image',
            'thumbnail',
            'image',
            'price',
            'special_price',
            'manufacturer',
            'visibility'
        ];
    }

    /**
     * Get the additional attribute fields for the store
     *
     * @param Mage_Core_Model_Store $store
     * @return mixed
     */
    public function getAdditionalAttributes($store)
    {
        return Mage::getStoreConfig(self::PATH_FF_ADDITIONAL_ATTRIBUTES, $store->getId());
    }

    /**
     * Get all the attributes for a given product and store
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    public function getAttributes($product, $store)
    {
        $data = [];
        $attributesString = '';
        $additionalAttributes = $this->getAdditionalAttributes($store);

        if (!empty($additionalAttributes)) {
            $attributeCodes = explode(',', $additionalAttributes);
            $attrCount = 0;

            foreach ($attributeCodes as $attributeCode) {
                $attribute      = $this->getAttributeModel($attributeCode);
                $attributeValue = $product->getData($attribute->getAttributeCode());

                if (empty($attributeValue)) {
                    continue;
                }

                $frontendInput = $attribute->getFrontendInput();
                $values = [];
                switch ($frontendInput) {
                    case 'select':
                        $values[] =  $product->getAttributeText($attributeCode);
                        break;
                    case 'multiselect':
                        $values[] = implode(',', (array) $product->getAttributeText($attributeCode));
                        break;
                    case 'price':
                        $values[] = number_format(round(floatval($attributeValue), 2), 2);
                        break;
                    case 'boolean':
                        $values[] = $attributeValue ? $this->__('Yes') : $this->__('No');
                        break;
                    default:
                        $values[] = $attributeValue;
                        break;
                }

                $label = $attribute->getStoreLabel($store->getId());
                if (empty($label)) {
                    $label = $attribute->getAttributeCode();
                }

                foreach ($values as $value) {
                    if ($attrCount < self::ATTRIBUTE_LIMIT) {
                        $data[] = $this->cleanValue($label, true) . '=' . $this->cleanValue($value, true);
                        $attrCount++;
                    }
                }
            }
            if (!empty($data)) {
                $attributesString = self::ATTRIBUTE_DELIMITER . implode(self::ATTRIBUTE_DELIMITER, $data) . self::ATTRIBUTE_DELIMITER;
            }
        }

        return $attributesString;
    }

    /**
     * @param $store
     * @return array
     */
    public function getProductVisibility($store)
    {
        return explode(',', Mage::getStoreConfig(self::PATH_FF_PRODUCT_VISIBILITY, $store));
    }

    /**
     * @param $store
     *
     * @return array
     */
    public function getCustomerGroupsForPriceExport($store)
    {
        return explode(',', Mage::getStoreConfig(self::PATH_FF_PRICE_CUSTOMER_GROUPS, $store));
    }

    /**
     * Returns category path as url encoded category names separated by slashes
     *
     * @param Mage_Catalog_Model_Category $category
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    private function getCategoryPathByCategory($category, $store)
    {
        $rootCategoryId = $store->getRootCategoryId();
        $treeRootId = Mage_Catalog_Model_Category::TREE_ROOT_ID;

        if (in_array($category->getId(), [$rootCategoryId, $treeRootId])) {
            return '';
        }

        if (isset($this->categories[$category->getParentId()])) {
            $parentCategory = $this->categories[$category->getParentId()];
        } else {
            $parentCategory = Mage::getModel('catalog/category')->load($category->getParentId());
            $this->categories[$parentCategory->getId()] = $parentCategory;
        }

        $path       = urlencode($category->getName());
        $parentPath = $this->getCategoryPathByCategory($parentCategory, $store);
        $path       = $parentPath === '' ? $path : $parentPath . '/' . $path;

        return $path;
    }

    /**
     * Get the product number of the parent product if existing
     *
     * @param integer $id
     * @return false|integer
     */
    private function getProductParentIdByProductId($id)
    {
        $parentByChild = $this->configurableProductModel->getParentIdsByChild($id);
        $parentId      = false;

        if (isset($parentByChild[0])) {
            $parentId = $parentByChild[0];
        }

        return $parentId;
    }

    /**
     * Cleanup a value for export
     *
     * @param string $value
     * @param bool $isMultiAttributeValue
     * @return string
     */
    private function cleanValue($value, $isMultiAttributeValue = false)
    {
        if (is_numeric($value)) {
            return $value;
        }
        $value = strip_tags(nl2br($value));
        $value = preg_replace("/\r|\n/", '', $value);
        $value = addcslashes($value, '\\');

        if ($isMultiAttributeValue) {
            // do not allow special chars in values
            $value = preg_replace('/([^A-Za-z0-9 -])+/', '', $value);
            // reduce multiple spaces to one
            $value = preg_replace('/\s\s+/', ' ', $value);
        }

        return trim($value);
    }

    /**
     *
     * @param Mage_Catalog_Model_Product $product
     * @param string $attributeCode
     * @return mixed
     */
    private function getConfiguredAttributeValue($product, $attributeCode)
    {
        $attribute = $this->getAttributeModel($attributeCode);
        if (in_array($attribute->getFrontendInput(), ['select', 'multiselect'])) {
            $value = $product->getAttributeText($attributeCode);
        } else {
            $value = $product->getData($attributeCode);
        }

        return $value;
    }

    /**
     * @param string $attributeCode
     *
     * @return Mage_Eav_Model_Entity_Attribute
     */
    private function getAttributeModel($attributeCode)
    {
        if (isset($this->attributes[$attributeCode])) {
            return $this->attributes[$attributeCode];
        }

        return Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $attributeCode);
    }
}
