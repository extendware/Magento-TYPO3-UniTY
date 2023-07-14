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
namespace WebVision\Unity\Model\Config\Source\Web;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Protocol extends ArrayAbstract
{
    const HTTP = 0;
    const HTTPS = 1;
    const CURRENT = 2;

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            self::HTTP => __('HTTP only'),
            self::HTTPS => __('HTTPS only'),
            self::CURRENT => __('Currently used'),
        ];
    }
}
