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
 * @copyright   Copyright (c) 2001-2018 web-vision GmbH (http://www.web-vision.de)
 * @license     <!--LICENSEURL-->
 * @author      WebVision <http://www.web-vision.de>
 */
namespace WebVision\Unity\Model\TYPO3;

use WebVision\Unity\Model\ResourceModel\TYPO3\PagesLanguageOverlay as PagesLanguageOverlayResourceModel;

class PagesLanguageOverlay extends AbstractModule
{
    protected function _construct()
    {
        $this->_init(PagesLanguageOverlayResourceModel::class);
    }
}
