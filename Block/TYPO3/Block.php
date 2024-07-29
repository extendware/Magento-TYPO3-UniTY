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
namespace WebVision\Unity\Block\TYPO3;

class Block extends AbstractBlock
{
    protected $_template = 'block.phtml';

    protected function _construct()
    {
        $this->setData('mode', 'page');

        if (!$this->hasData('page_uid')
            && $this->getData('mode') !== 'element'
            && ($pageId = $this->_TYPO3Helper->getPageId())
        ) {
            $this->setData('page_uid', $pageId);
        }

        if (!$this->hasData('cache_lifetime')) {
            $this->setData('cache_lifetime', $this->_dataHelper->getMagWidgetCacheLifetime());
        }

        parent::_construct();
    }

    public function getWrapperClass()
    {
        $wrapperClasses = [];

        if ($this->hasData('wrapper_class')) {
            $wrapperClasses = explode(' ', $this->getData('wrapper_class'));
        }

        if ($this->getRequest()->getParam('show_unity', false)) {
            $wrapperClasses[] = 'show-unity';
        }

        return $wrapperClasses ? ' ' . implode(' ', $wrapperClasses) : '';
    }

    public function _prepareLayout() {
        $typo3Head = $this->_TYPO3Model
            ->load('head', ['page_uid' => $this->getData('page_uid')]);

        if ($typo3Head->getHasJsonContent()) {
            if ($typo3Head->getTitle()) {
                $this->pageConfig->getTitle()->set($typo3Head->getTitle());
            }

            foreach ($typo3Head->getData() as  $name => $metaTag) {
                switch ($name) {
                    case 'description':
                        $this->pageConfig->setMetaData('description', $metaTag);
                        break;
                    case 'keywords':
                        $this->pageConfig->setMetaData('keywords', $metaTag);
                        break;
                    case 'abstract':
                        $this->pageConfig->setMetaData('abstract', $metaTag);
                        break;
                    case 'seo_title':
                        $this->pageConfig->setMetaData('title', $metaTag);
                        break;
                    default:
                        // $this->pageConfig->setMetaData($name, $metaTag);
                }
            }
        }

        return parent::_prepareLayout();
    }
}
