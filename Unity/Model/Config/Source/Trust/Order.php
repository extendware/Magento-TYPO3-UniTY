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
namespace WebVision\Unity\Model\Config\Source\Trust;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Order extends ArrayAbstract
{
    const WHITELIST_BLACKLIST = 0;
    const BLACKLIST_WHITELIST = 1;

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            static::WHITELIST_BLACKLIST => __('Whitelist, Blacklist'),
            static::BLACKLIST_WHITELIST => __('Blacklist, Whitelist'),
        ];
    }
}
