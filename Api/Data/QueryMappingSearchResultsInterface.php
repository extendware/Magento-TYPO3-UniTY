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

interface QueryMappingSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get query_mapping list.
     *
     * @return \WebVision\Unity\Api\Data\QueryMappingInterface[]
     */
    public function getItems();

    /**
     * Set magento_key list.
     *
     * @param \WebVision\Unity\Api\Data\QueryMappingInterface[] $items
     *
     * @return $this
     */
    public function setItems(array $items);
}
