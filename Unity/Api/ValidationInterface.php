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
namespace WebVision\Unity\Api;

interface ValidationInterface
{
    /**
     * Validates the username and token.
     *
     * @api
     *
     * @param string $username
     * @param string $token
     *
     * @return string If the cache could be cleared or not.
     */
    public function validateToken($username, $token);
}
