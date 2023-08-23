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
use Magento\Framework\Api\ExtensibleDataObjectConverter;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use WebVision\Unity\Api\Data\QueryMappingInterfaceFactory;
use WebVision\Unity\Api\Data\QueryMappingSearchResultsInterfaceFactory;
use WebVision\Unity\Api\QueryMappingRepositoryInterface;
use WebVision\Unity\Model\ResourceModel\QueryMapping as ResourceQueryMapping;
use WebVision\Unity\Model\ResourceModel\QueryMapping\CollectionFactory as QueryMappingCollectionFactory;

class QueryMappingRepository implements QueryMappingRepositoryInterface
{
    protected $resource;

    protected $extensibleDataObjectConverter;
    protected $searchResultsFactory;

    protected $dataQueryMappingFactory;

    private $storeManager;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $queryMappingFactory;

    protected $extensionAttributesJoinProcessor;

    private $collectionProcessor;

    protected $queryMappingCollectionFactory;

    /**
     * @param ResourceQueryMapping $resource
     * @param QueryMappingFactory $queryMappingFactory
     * @param QueryMappingInterfaceFactory $dataQueryMappingFactory
     * @param QueryMappingCollectionFactory $queryMappingCollectionFactory
     * @param QueryMappingSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param JoinProcessorInterface $extensionAttributesJoinProcessor
     * @param ExtensibleDataObjectConverter $extensibleDataObjectConverter
     */
    public function __construct(
        ResourceQueryMapping $resource,
        QueryMappingFactory $queryMappingFactory,
        QueryMappingInterfaceFactory $dataQueryMappingFactory,
        QueryMappingCollectionFactory $queryMappingCollectionFactory,
        QueryMappingSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor,
        JoinProcessorInterface $extensionAttributesJoinProcessor,
        ExtensibleDataObjectConverter $extensibleDataObjectConverter
    ) {
        $this->resource = $resource;
        $this->queryMappingFactory = $queryMappingFactory;
        $this->queryMappingCollectionFactory = $queryMappingCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataQueryMappingFactory = $dataQueryMappingFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor;
        $this->extensionAttributesJoinProcessor = $extensionAttributesJoinProcessor;
        $this->extensibleDataObjectConverter = $extensibleDataObjectConverter;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \WebVision\Unity\Api\Data\QueryMappingInterface $queryMapping
    ) {
        /* if (empty($queryMapping->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $queryMapping->setStoreId($storeId);
        } */

        $queryMappingData = $this->extensibleDataObjectConverter->toNestedArray(
            $queryMapping,
            [],
            \WebVision\Unity\Api\Data\QueryMappingInterface::class
        );

        $queryMappingModel = $this->queryMappingFactory->create()->setData($queryMappingData);

        try {
            $this->resource->save($queryMappingModel);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the queryMapping: %1',
                $exception->getMessage()
            ));
        }

        return $queryMappingModel->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function get($queryMappingId)
    {
        $queryMapping = $this->queryMappingFactory->create();
        $this->resource->load($queryMapping, $queryMappingId);
        if (!$queryMapping->getId()) {
            throw new NoSuchEntityException(__('query_mapping with id "%1" does not exist.', $queryMappingId));
        }

        return $queryMapping->getDataModel();
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->queryMappingCollectionFactory->create();

        $this->extensionAttributesJoinProcessor->process(
            $collection,
            \WebVision\Unity\Api\Data\QueryMappingInterface::class
        );

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model->getDataModel();
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());

        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \WebVision\Unity\Api\Data\QueryMappingInterface $queryMapping
    ) {
        try {
            $queryMappingModel = $this->queryMappingFactory->create();
            $this->resource->load($queryMappingModel, $queryMapping->getQueryMappingId());
            $this->resource->delete($queryMappingModel);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the query_mapping: %1',
                $exception->getMessage()
            ));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($queryMappingId)
    {
        return $this->delete($this->get($queryMappingId));
    }
}
