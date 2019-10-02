<?php

use Mage_Catalog_Model_Category as Category;

class Omikron_Factfinder_Helper_Category extends Mage_Core_Helper_Abstract
{
    /** @var string */
    private $param = 'CategoryPath';

    /** @var string[] */
    private $initial = ['navigation=true'];

    public function getPath()
    {
        $path     = 'ROOT';
        $value    = $this->initial;
        $category = Mage::registry('current_category');
        if ($category instanceof Category) {
            foreach ($category->getParentCategories() as $item) {
                $value[] = sprintf("filter{$this->param}%s=%s", $path, urlencode($item->getName()));
                $path    .= urlencode('/' . $item->getName());
            }
        }
        return implode(',', $value);
    }
}
