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
namespace WebVision\Unity\Api;

interface QueryMappingRepositoryInterface
{
    /**
     * Save query_mapping
     *
     * @param \WebVision\Unity\Api\Data\QueryMappingInterface $queryMapping
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function save(
        \WebVision\Unity\Api\Data\QueryMappingInterface $queryMapping
    );

    /**
     * Retrieve query_mapping
     *
     * @param string $queryMappingId
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface
     */
    public function get($queryMappingId);

    /**
     * Retrieve query_mapping matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingSearchResultsInterface
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete query_mapping
     *
     * @param \WebVision\Unity\Api\Data\QueryMappingInterface $queryMapping
     *
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @return bool true on success
     */
    public function delete(
        \WebVision\Unity\Api\Data\QueryMappingInterface $queryMapping
    );

    /**
     * Delete query_mapping by ID
     *
     * @param string $queryMappingId
     *
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     *
     * @return bool true on success
     */
    public function deleteById($queryMappingId);
}
