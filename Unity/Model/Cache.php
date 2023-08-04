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
use Magento\Framework\App\Cache\Frontend\Pool;
use Magento\Framework\App\Cache\TypeListInterface;
use WebVision\Unity\Api\CacheInterface;

class Cache implements CacheInterface
{
    /**
     * @var \Magento\Framework\App\Cache\TypeListInterface
     */
    protected $_cacheTypeList;

    /**
     * @var \Magento\Framework\App\Cache\Frontend\Pool
     */
    protected $_cacheFrontendPool;

    public function __construct(
        TypeListInterface $cacheTypeList,
        Pool $cacheFrontendPool
    ) {
        $this->_cacheTypeList = $cacheTypeList;
        $this->_cacheFrontendPool = $cacheFrontendPool;
    }

    /**
     * Clears all magento caches
     *
     * @api
     *
     * @param string $cacheType
     *
     * @return bool If the cache could be cleared or not.
     */
    public function clearAllCaches($cacheType)
    {
        try {
            $types = [
                'config',
                'layout',
                'block_html',
                'collections',
                'reflection',
                'db_ddl',
                'eav',
                'config_integration',
                'config_integration_api',
                'full_page',
                'translate',
                'config_webservice',
            ];
            // Checking the type of cache requested
            switch ($cacheType) {
                case 'blocks':
                    $types = [
                        'full_page',
                    ];

                    break;
                // Do nothing for other requests
                // As the types array is already populated
                default:
                    break;
            }
            foreach ($types as $type) {
                $this->_cacheTypeList->cleanType($type);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
