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

class Item
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

    /**
     * Get filter item url
     *
     * @param mixed $result
     *
     * @return string
     */
    public function afterGetUrl(
        \Magento\Catalog\Model\Layer\Filter\Item $subject,
        $result
    ) {
        if (!$this->_unityHelper->getSeoFilterStatus()) {
            return $result;
        }

        if ($this->_filterHelper->getCurrentCategoryUrl()) {
            $categoryUrl = $this->_filterHelper->getCurrentUrl();
            $attributeLabel = $subject->getFilter()->getRequestVar();
            $attribeVal = $subject->getValue();

            if (strtolower($attributeLabel) == 'cat') {
                $categoryId = $attribeVal;

                return $this->_filterHelper->getCategoryUrl($categoryId);
            }

            if ($attributeLabel != 'price') {
                $attribeVal = $this->_filterHelper->getAttributeIdfromText($attributeLabel, $subject->getValue());
            }

            return $this->_filterHelper->generateFilterUrl($categoryUrl, $attributeLabel, $attribeVal);
        }

        return $result;
    }

    /**
     * Get url for remove item from filter
     *
     * @param mixed $result
     *
     * @return string
     */
    public function afterGetRemoveUrl(
        \Magento\Catalog\Model\Layer\Filter\Item $subject,
        $result
    ) {
        if (!$this->_unityHelper->getSeoFilterStatus()) {
            return $result;
        }

        $attributeLabel = $subject->getFilter()->getRequestVar();
        $attribeVal = $subject->getValue();
        $currentUrl = $this->_filterHelper->getCurrentUrl();

        if ($attributeLabel == 'price') {
            return $currentUrl = $this->_filterHelper->clearPriceFilterFromUrl($currentUrl);
        }

        $attribeVal = $this->_filterHelper->getAttributeIdfromText($attributeLabel, $attribeVal);
        $attribeVal = strtolower($attribeVal);

        if ($this->_filterHelper->getSeoKey($attribeVal)) {
            $attribeVal = $this->_filterHelper->getSeoKey($attribeVal);
        }
        $currentUrl = urldecode($currentUrl);

        if ($this->_filterHelper->getCurrentCategoryUrl()) {
            return trim(str_replace('/' . $attribeVal, '', $currentUrl));
        }

        return $result;
    }
}
