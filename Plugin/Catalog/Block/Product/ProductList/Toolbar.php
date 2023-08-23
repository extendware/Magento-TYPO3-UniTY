<?php
namespace WebVision\Unity\Plugin\Catalog\Block\Product\ProductList;

class Toolbar
{
    public function afterGetTemplate(\Magento\Catalog\Block\Product\ProductList\Toolbar $subject, $result)
    {
        return 'WebVision_Unity::toolbar.phtml';
    }
}
