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
namespace WebVision\Unity\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use WebVision\Unity\Helper\Filter as Helper;

/**
 * Class Category
 */
class HelperData implements ArgumentInterface
{
    /**
     * @var WebVision\Unity\Helper
     */
    public $_helper;

    /**
     * ProductData constructor.
     *
     * @param CategoryFactory $categoryFactory
     */
    public function __construct(
        Helper $helper
    ) {
        $this->_helper = $helper;
    }

    public function generateSorterUrl($value)
    {
        return $this->_helper->generateSorterUrl($this->_helper->getCurrentUrl(), Helper::SORTER, $value);
    }

    public function generateLimiterUrl($value)
    {
        return $this->_helper->generateLimiterUrl($this->_helper->getCurrentUrl(), Helper::LIMITER, $value);
    }
}
