<?php
/**
 * web-vision GmbH
 *
 * NOTICE OF LICENSE
 *
 * <!--LICENSETEXT-->
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.web-vision.de for more information.
 *
 * @category    WebVision
 *
 * @copyright   Copyright (c) 2001-2021 web-vision GmbH (https://www.web-vision.de)
 * @license     <!--LICENSEURL-->
 * @author      Parth Trivedi <parth@web-vision.de>
 */
namespace WebVision\Unity\Plugin\Magento\Catalog\Model\Layer\Filter;

class State
{
    protected $_filterHelper;

    protected $_unityHelper;

    public function __construct(
        \WebVision\Unity\Helper\Filter $filterHelper,
        \WebVision\Unity\Helper\Data $unityHelper
    ) {
        $this->_filterHelper = $filterHelper;
        $this->_unityHelper = $unityHelper;
    }

    public function afterGetClearUrl(
        \Magento\LayeredNavigation\Block\Navigation\State $subject,
        $result
    ) {
        if (!$this->_unityHelper->getSeoFilterStatus()) {
            return $result;
        }

        $currentUrl = $this->_filterHelper->getCurrentUrl();
        $currentUrl = $this->_filterHelper->clearPriceFilterFromUrl($currentUrl);
        if ($this->_filterHelper->getCategorySuffix()) {
            $currentUrl = $this->_filterHelper->right_trim($currentUrl, $this->_filterHelper->getCategorySuffix());
        }

        $urlKey_explode = explode('/', $currentUrl);
        $allFilters = $this->_filterHelper->getFilterDetails($urlKey_explode);

        $finalFilters = [];
        if (!empty($allFilters)) {
            foreach ($allFilters as $key => $value) {
                $attributeLabel = $key;
                $attribeVal = $value;
                if ($key != 'price') {
                    $attribeVal = $this->_filterHelper->getAttributeIdfromText($key, $value);
                    $attribeVal = strtolower($attribeVal);
                    if ($this->_filterHelper->getSeoKey($attribeVal)) {
                        $attribeVal = $this->_filterHelper->getSeoKey($attribeVal);
                    }
                }
                $finalFilters[] = '/' . $attribeVal;
            }

            $currentUrl = urldecode($currentUrl);

            if ($this->_filterHelper->getCurrentCategoryUrl()) {
                return trim(str_replace($finalFilters, '', $currentUrl)) . $this->_filterHelper->getCategorySuffix();
            }
        }

        return $result;
    }
}
