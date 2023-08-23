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
namespace WebVision\Unity\Model;

use Magento\Framework\Api\DataObjectHelper;
use WebVision\Unity\Api\Data\QueryMappingInterface;
use WebVision\Unity\Api\Data\QueryMappingInterfaceFactory;

class QueryMapping extends \Magento\Framework\Model\AbstractModel
{
    protected $dataObjectHelper;

    protected $_eventPrefix = 'webvision_unity_query_mapping';
    protected $query_mappingDataFactory;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param QueryMappingInterfaceFactory $query_mappingDataFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param \WebVision\Unity\Model\ResourceModel\QueryMapping $resource
     * @param \WebVision\Unity\Model\ResourceModel\QueryMapping\Collection $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        QueryMappingInterfaceFactory $query_mappingDataFactory,
        DataObjectHelper $dataObjectHelper,
        \WebVision\Unity\Model\ResourceModel\QueryMapping $resource,
        \WebVision\Unity\Model\ResourceModel\QueryMapping\Collection $resourceCollection,
        array $data = []
    ) {
        $this->query_mappingDataFactory = $query_mappingDataFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * Retrieve query_mapping model with query_mapping data
     *
     * @return QueryMappingInterface
     */
    public function getDataModel()
    {
        $query_mappingData = $this->getData();

        $query_mappingDataObject = $this->query_mappingDataFactory->create();
        $this->dataObjectHelper->populateWithArray(
            $query_mappingDataObject,
            $query_mappingData,
            QueryMappingInterface::class
        );

        return $query_mappingDataObject;
    }
}
