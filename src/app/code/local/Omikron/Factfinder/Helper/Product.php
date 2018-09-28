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
            case "CategoryPath":
            case "Availability":
            case "MagentoEntityId":
                $method = 'get' . $attribute;
                return call_user_func(array($this, $method), $product);
            case "ImageUrl":
            case "Manufacturer":
            case "Attributes":
            case "EAN":
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
            $parentProduct = Mage::getModel('catalog/product')->load($parentId);

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
     * @return string
     */
    private function getProductUrl($product)
    {
        return $product->getUrlInStore();
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
     * @return string
     */
    private function getCategoryPath($product)
    {
        $categoryIds = $product->getCategoryIds();
        $path = [];
        $attrCount = 0;

        foreach ($categoryIds as $categoryId) {
            /** @var Mage_Catalog_Model_Category $category */
            $category = Mage::getModel('catalog/category')->load($categoryId);
            if ($attrCount < self::ATTRIBUTE_LIMIT && $category->getIsActive()) {
                $categoryPath = $this->getCategoryPathByCategory($category);
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
                /** @var Mage_Eav_Model_Entity_Attribute $attribute */
                $attribute = Mage::getModel('eav/entity_attribute')->loadByCode('catalog_product', $attributeCode);
                $attributeValue = $product->getData($attribute->getAttributeCode());

                if (empty($attributeValue)) {
                    continue;
                }

                $frontendInput = $attribute->getFrontendInput();
                $values = [];

                if (in_array($frontendInput, ['select', 'multiselect'])) {
                    // value holds single or multiple options IDs
                    foreach (explode(",", $attributeValue) as $optionId) {
                        $optionLabel = $attribute->getSource()->getOptionText($optionId);
                        $values[] = $optionLabel;
                    }
                } else if ($frontendInput == 'price') {
                    $values[] = number_format(round(floatval($attributeValue), 2), 2);
                } else if ($frontendInput == 'boolean') {
                    $values[] = $attributeValue ? "Yes" : "No";
                } else {
                    $values[] = $attributeValue;
                }

                $label = $product->getResource()->getAttribute($attribute->getAttributeCode())->getStoreLabel();

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
     * @return string
     */
    private function getCategoryPathByCategory($category)
    {
        $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
        $treeRootId = Mage_Catalog_Model_Category::TREE_ROOT_ID;

        if (in_array($category->getId(), [$rootCategoryId, $treeRootId])) {
            return '';
        }

        $path = urlencode($category->getName());
        $parentPath = $this->getCategoryPathByCategory(Mage::getModel('catalog/category')->load($category->getParentId()));
        $path = $parentPath === '' ? $path : $parentPath . '/' . $path;

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
        $parentByChild = Mage::getModel('catalog/product_type_configurable')->getParentIdsByChild($id);
        $parentId = false;

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
