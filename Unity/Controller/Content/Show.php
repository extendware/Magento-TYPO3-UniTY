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
namespace WebVision\Unity\Controller\Content;

use Magento\Framework\Controller\ResultFactory;

class Show extends \Magento\Framework\App\Action\Action
{
    /**
     * Listing all images in gallery
     *  -@param gallery id
     */
    public function execute()
    {
        return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
    }
}
