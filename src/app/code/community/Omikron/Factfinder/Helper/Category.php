<?php

use Mage_Catalog_Model_Category as Category;

class Omikron_Factfinder_Helper_Category extends Mage_Core_Helper_Abstract
{
    /** @var string */
    protected $param = 'CategoryPath';

    /** @var string[] */
    protected $initial = ['navigation=true'];

    public function getPath(): string
    {
        $value    = $this->initial;
        $category = Mage::registry('current_category');
        if (!($category instanceof Category)) {
            return '';
        }

        $categories = $this->getCategoryPath($category);
        switch (Mage::helper('factfinder')->getVersion()) {
            case 'ng':
                $value[] = sprintf('filter=%s', urlencode($this->param . ':' . implode('/', $categories)));
                break;
            default:
                $path = 'ROOT';
                foreach ($categories as $item) {
                    $value[] = vsprintf('filter%s%s=%s', array_map('urlencode', [$this->param, $path, $item]));
                    $path    = "{$path}/{$item}";
                }
                break;
        }

        return implode(',', $value);
    }

    protected function getCategoryPath(Category $category): array
    {
        return array_map(function (Category $item): string {
            return (string) $item->getName();
        }, $category->getParentCategories());
    }
}
