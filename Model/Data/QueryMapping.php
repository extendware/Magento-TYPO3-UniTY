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
 * @copyright   Copyright (c) 2001-2021 web-vision GmbH (https://www.web-vision.de)
 * @license     <!--LICENSEURL-->
 * @author      Parth Trivedi <parth@web-vision.de>
 */
declare(strict_types=1);
namespace WebVision\Unity\Model\Data;

use WebVision\Unity\Api\Data\QueryMappingInterface;

class QueryMapping extends \Magento\Framework\Api\AbstractExtensibleObject implements QueryMappingInterface
{
    /**
     * Get query_mapping_id
     *
     * @return string|null
     */
    public function getQueryMappingId()
    {
        return $this->_get(self::QUERY_MAPPING_ID);
    }

    /**
     * Set query_mapping_id
     *
     * @param string $queryMappingId
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setQueryMappingId($queryMappingId)
    {
        return $this->setData(self::QUERY_MAPPING_ID, $queryMappingId);
    }

    /**
     * Get magento_key
     *
     * @return string|null
     */
    public function getMagentoKey()
    {
        return $this->_get(self::MAGENTO_KEY);
    }

    /**
     * Set magento_key
     *
     * @param string $magentoKey
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setMagentoKey($magentoKey)
    {
        return $this->setData(self::MAGENTO_KEY, $magentoKey);
    }

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingExtensionInterface|null
     */
    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    /**
     * Set an extension attributes object.
     *
     * @param \WebVision\Unity\Api\Data\QueryMappingExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \WebVision\Unity\Api\Data\QueryMappingExtensionInterface $extensionAttributes
    ) {
        return $this->_setExtensionAttributes($extensionAttributes);
    }

    /**
     * Get seo_key
     *
     * @return string|null
     */
    public function getSeoKey()
    {
        return $this->_get(self::SEO_KEY);
    }

    /**
     * Set seo_key
     *
     * @param string $seoKey
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setSeoKey($seoKey)
    {
        return $this->setData(self::SEO_KEY, $seoKey);
    }

    /**
     * Get store_id
     *
     * @return string|null
     */
    public function getStoreId()
    {
        return $this->_get(self::STORE_ID);
    }

    /**
     * Set store_id
     *
     * @param string $storeId
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setStoreId($storeId)
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * Get position
     *
     * @return string|null
     */
    public function getPosition()
    {
        return $this->_get(self::POSITION);
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }
}
