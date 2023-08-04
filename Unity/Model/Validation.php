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
namespace WebVision\Unity\Model;

use Exception;
use Magento\Framework\Filesystem\DirectoryList;
use WebVision\Unity\Api\ValidationInterface;

class Validation implements ValidationInterface
{
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $_dir;

    /**
     * Validation constructor.
     *
     * @param \Magento\Framework\Filesystem\DirectoryList $dir
     */
    public function __construct(DirectoryList $dir)
    {
        $this->_dir = $dir;
    }

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
    public function validateToken($username, $token)
    {
        try {
            $hashDir = $this->_dir->getPath('var') . DIRECTORY_SEPARATOR . 'hash' . DIRECTORY_SEPARATOR;
            $hash = file_get_contents($hashDir . $username);

            return $hash === $token;
        } catch (Exception $e) {
        } catch (\Throwable $t) {
        }

        return false;
    }
}
