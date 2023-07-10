<?php
namespace WebVision\Unity\Api;

interface CacheInterface
{
    /**
     * Clears all magento caches
     *
     * @api
     *
     * @param string $cacheType
     *
     * @return bool If the cache could be cleared or not.
     */
    public function clearAllCaches($cacheType);
}
