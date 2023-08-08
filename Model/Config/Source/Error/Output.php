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
namespace WebVision\Unity\Model\Config\Source\Error;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Output extends ArrayAbstract
{
    const HTML = 0;
    const COMMENT = 1;
    const LOG = 2;

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            static::HTML => __('As HTML Code'),
            static::COMMENT => __('As HTML Comment'),
            static::LOG => __('As Log file'),
        ];
    }
}
