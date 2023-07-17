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
 * @author      Fenil Timbadiya <fenil@web-vision.de>
 */
namespace WebVision\Unity\Model\TYPO3;

use WebVision\Unity\Model\ResourceModel\TYPO3\Ttcontent as TtcontentResourceModel;

class Ttcontent extends AbstractModule
{
    protected function _construct()
    {
        $this->_init(TtcontentResourceModel::class);
    }

    public function isColumnPresent($pid, $colPos)
    {
        return $this->_getResource()
            ->isColumnPresent($this, $pid, $colPos);
    }

    public function isElementPresent($uid)
    {
        return $this->_getResource()
            ->isElementPresent($this, $uid);
    }
}
