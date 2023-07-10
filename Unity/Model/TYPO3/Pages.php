<?php
namespace WebVision\Unity\Model\TYPO3;

use Magento\Framework\Model\Context;
use WebVision\Unity\Model\ResourceModel\TYPO3\Pages as PagesResourceModel;

class Pages extends AbstractModule
{
    public function __construct(
        Context $context,
        \Magento\Framework\Registry $registry,
        PagesResourceModel $resource,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $resource,
            $resourceCollection,
            $data
        );
    }

    protected function _construct()
    {
        $this->_init(PagesResourceModel::class);
    }
}
