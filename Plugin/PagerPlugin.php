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
namespace WebVision\Unity\Plugin;

class PagerPlugin
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
     * Get page variable name
     *
     * @param object $subject
     * @param string $result
     *
     * @return string
     */
    public function afterGetPageVarName(
        \Magento\Theme\Block\Html\Pager $subject,
        $result
    ) {
        if (!$this->_unityHelper->getSeoPaginationStatus()) {
            return $result;
        }

        return 'page';
    }

    /**
     * Retrieve page URL
     *
     * @param object $subject
     * @param string $result
     * @param mixed $params
     *
     * @return string
     */
    public function afterGetPagerUrl(
        \Magento\Theme\Block\Html\Pager $subject,
        $result,
        $params
    ) {
        if (!$this->_unityHelper->getSeoPaginationStatus()) {
            return $result;
        }

        $paramVal = 1;
        if ($this->_filterHelper->getCurrentCategoryUrl()) {
            $currentUrl = $this->_filterHelper->getCurrentUrl();

            if ($params['page']) {
                $paramVal = $params['page'];
            }

            return $this->_filterHelper->generatePagerUrl($currentUrl, 'page', $paramVal);
        }

        return $result;
    }

    /**
     * Return current page
     *
     * @param object $subject
     * @param mixed $result
     *
     * @return int
     */
    public function afterGetCurrentPage(
        \Magento\Theme\Block\Html\Pager $subject,
        $result
    ) {
        if (!$this->_unityHelper->getSeoPaginationStatus()) {
            return $result;
        }

        if ($this->_filterHelper->getCurrentCategoryUrl()) {
            $currentUrl = $this->_filterHelper->getCurrentUrl();
            if ($this->_filterHelper->getCategorySuffix()) {
                $currentUrl = rtrim($currentUrl, $this->_filterHelper->getCategorySuffix());
            }
            $urlParts = parse_url($currentUrl);
            $pathArray = explode('/', $urlParts['path']);

            $out = 1;
            foreach ($pathArray as $key => $pathSingle) {
                $attVal = $pathSingle;
                if ($attVal == 'page') {
                    if (isset($pathArray[$key + 1])) {
                        $out = $pathArray[$key + 1];
                    }
                }

                if ($this->_filterHelper->getMagentoKey($pathSingle)) {
                    $pathSingle = $this->_filterHelper->getMagentoKey($pathSingle);
                    if (strpos($pathSingle, 'page') === 0) {
                        $out = substr($pathSingle, -1);
                    }
                }
            }

            return $out;
        }

        return $result;
    }
}
