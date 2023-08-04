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
 * needs please refer to https://www.extendware.com for more information.
 *
 * @category    Extendware
 *
 * @copyright   Copyright (c) 2001-2023 web-vision GmbH (https://www.extendware.com)
 * @license     <!--LICENSEURL-->
 * @author      Extendware, by web-vision GmbH  <https://www.extendware.com>
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
