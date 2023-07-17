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
namespace WebVision\Unity\Model\Config\Source\Web;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Domain extends ArrayAbstract
{
    const MAGENTO = 0;
    const OWN = 1;

    public function toArray()
    {
        return [
            static::MAGENTO => __('Same as Magento'),
            static::OWN => __('Own domain'),
        ];
    }
}
