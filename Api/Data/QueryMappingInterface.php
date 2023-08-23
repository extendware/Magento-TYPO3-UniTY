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
namespace WebVision\Unity\Api\Data;

interface QueryMappingInterface extends \Magento\Framework\Api\ExtensibleDataInterface
{
    const POSITION = 'position';
    const STORE_ID = 'store_id';
    const SEO_KEY = 'seo_key';
    const MAGENTO_KEY = 'magento_key';
    const QUERY_MAPPING_ID = 'query_mapping_id';

    /**
     * Get query_mapping_id
     *
     * @return string|null
     */
    public function getQueryMappingId();

    /**
     * Set query_mapping_id
     *
     * @param string $queryMappingId
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setQueryMappingId($queryMappingId);

    /**
     * Get magento_key
     *
     * @return string|null
     */
    public function getMagentoKey();

    /**
     * Set magento_key
     *
     * @param string $magentoKey
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setMagentoKey($magentoKey);

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \WebVision\Unity\Api\Data\QueryMappingExtensionInterface $extensionAttributes
     *
     * @return $this
     */
    public function setExtensionAttributes(
        \WebVision\Unity\Api\Data\QueryMappingExtensionInterface $extensionAttributes
    );

    /**
     * Get seo_key
     *
     * @return string|null
     */
    public function getSeoKey();

    /**
     * Set seo_key
     *
     * @param string $seoKey
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setSeoKey($seoKey);

    /**
     * Get store_id
     *
     * @return string|null
     */
    public function getStoreId();

    /**
     * Set store_id
     *
     * @param string $storeId
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setStoreId($storeId);

    /**
     * Get position
     *
     * @return string|null
     */
    public function getPosition();

    /**
     * Set position
     *
     * @param string $position
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function setPosition($position);
}
