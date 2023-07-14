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
namespace WebVision\Unity\Model\Config\Source\Typo3;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Realurl extends ArrayAbstract
{
    const V1 = 1;
    const V2 = 2;
    const V21 = 3;

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            static::V1 => __('realurl v1.x'),
            static::V2 => __('realurl v2.0'),
            static::V21 => __('realurl v2.1+'),
        ];
    }
}
