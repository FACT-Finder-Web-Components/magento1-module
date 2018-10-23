<?php

class Omikron_Factfinder_Helper_Product extends Mage_Core_Helper_Abstract
{
    // attributes properties
    const ATTRIBUTE_LIMIT = 1000;
    const ATTRIBUTE_DELIMITER = '|';

    // data transfer
    const PATH_FF_MANUFACTURER = "factfinder/data_transfer/ff_manufacturer";
    const PATH_FF_EAN = "factfinder/data_transfer/ff_ean";
    const PATH_FF_ADDITIONAL_ATTRIBUTES = "factfinder/data_transfer/ff_additional_attributes";

    // image placeholder
    const PATH_IMG_PLACEHOLDER = 'images/catalog/product/placeholder/image.jpg';

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
     * Omikron_Factfinder_Helper_Product constructor.
     */
    public function __construct()
    {
        $this->configurableProductModel = Mage::getModel('catalog/product_type_configurable');
        $this->imageHelper              = Mage::helper('catalog/image');
    }

    /**
     * Get the attribute value from magento product in corresponding store
     *
     * @param string $attribute
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return mixed|null
     */
    public function get($attribute, $product, $store)
    {
        switch ((string)$attribute) {
            case "ProductNumber":
            case "MasterProductNumber":
            case "Name":
            case "Description":
            case "Short":
            case "ProductUrl":
            case "Price":
            case "Availability":
            case "MagentoEntityId":
                $method = 'get' . $attribute;
                return call_user_func(array($this, $method), $product);
            case "ImageUrl":
            case "Manufacturer":
            case "Attributes":
            case "EAN":
            case "CategoryPath":
                $method = 'get' . $attribute;
                return call_user_func(array($this, $method), $product, $store);
            default:
                return null;
        }
    }

    /**
     * Get the product number
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    private function getProductNumber($product)
    {
        return $product->getData('sku');
    }

    /**
     * Get the master product number
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    private function getMasterProductNumber($product)
    {
        if ($parentId = $this->getProductParentIdByProductId($product->getId())) {
            if (isset($this->configurableProducts[$parentId])) {
                $parentProduct = $this->configurableProducts[$parentId];
            } else {
                $parentProduct = Mage::getModel('catalog/product')->load($parentId);
                $this->configurableProducts[$parentId] = $parentProduct;
            }

            return $parentProduct->getSku();
        } else {
            return $product->getSku();
        }
    }

    /**
     * Get the product name
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    private function getName($product)
    {
        return $product->getData('name');
    }

    /**
     * Get the product description
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    private function getDescription($product)
    {
        return $this->cleanValue($product->getData('description'));
    }

    /**
     * Get the product short description
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    private function getShort($product)
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
    private function getProductUrl($product, $store)
    {
        return $product->getUrlInStore(['_store' => $store->getCode()]);
    }

    /**
     * Retrieve product thumbnail url
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    private function getImageUrl($product, $store)
    {
        try {
            $imgSrc = (string) Mage::helper('catalog/image')->init($product, 'thumbnail')
                ->constrainOnly(true)
                ->keepAspectRatio(true)
                ->keepTransparency(true)
                ->resize(200, 200);
        } catch(Exception $e) {
            $imgSrc = Mage::getDesign()->getSkinUrl(self::PATH_IMG_PLACEHOLDER, array('_area'=>'frontend'));
        }

        return $imgSrc;
    }

    /**
     * Get the product price
     *
     * @param Mage_Catalog_Model_Product $product
     * @return string
     */
    private function getPrice($product)
    {
        return number_format($product->getData('price'), 2, '.', '');
    }

    /**
     * Get the product category
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return string
     */
    private function getCategoryPath($product, $store)
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
    private function getAvailability($product)
    {
        return (int) $product->isAvailable();
    }

    /**
     * Get the magento product entity id
     *
     * @param Mage_Catalog_Model_Product $product
     * @return int
     */
    private function getMagentoEntityId($product)
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
    private function getManufacturer($product, $store)
    {
        return $product->getData(Mage::getStoreConfig(self::PATH_FF_MANUFACTURER, $store->getId()));
    }

    /**
     * Get if the product EAN
     *
     * @param Mage_Catalog_Model_Product $product
     * @param Mage_Core_Model_Store $store
     * @return mixed
     */
    private function getEAN($product, $store)
    {
        return $product->getData(Mage::getStoreConfig(self::PATH_FF_EAN, $store->getId()));
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
            'manufacturer',
            'availability'
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
    private function getAttributes($product, $store)
    {
        $data = [];
        $attributesString = '';
        $additionalAttributes = $this->getAdditionalAttributes($store);

        if (!empty($additionalAttributes)) {
            $attributeCodes = explode(',', $additionalAttributes);
            $attrCount = 0;

            foreach ($attributeCodes as $attributeCode) {

                if (isset($this->attributes[$attributeCode])) {
                    /** @var Mage_Eav_Model_Entity_Attribute $attribute */
                    $attribute = $this->attributes[$attributeCode];
                } else {
                    /** @var Mage_Eav_Model_Entity_Attribute $attribute */
                    $attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $attributeCode);
                    $this->attributes[$attributeCode] = $attribute;
                }

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
                        $values[] = implode(',',  $product->getAttributeText($attributeCode));
                        break;
                    case 'price':
                        $values[] = number_format(round(floatval($attributeValue), 2), 2);
                        break;
                    case 'boolean':
                        $values[] = $attributeValue ? __("Yes") : __("No");
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
        $value = strip_tags(nl2br($value));
        $value = preg_replace("/\r|\n/", "", $value);
        $value = addcslashes($value, '\\');

        if ($isMultiAttributeValue) {
            // do not allow special chars in values
            $value = preg_replace('/([^A-Za-z0-9 -])+/', '', $value);
            // reduce multiple spaces to one
            $value = preg_replace('/\s\s+/', ' ', $value);
        }

        return trim($value);
    }
}
