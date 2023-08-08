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

    /**
     * init t3 model class & prepare block layout
    */
    public function _prepareLayout()
    {
        $typo3Head = $this->_TYPO3Model
            ->load('head', ['page_uid' => $this->getData('page_uid')]);

        if ($typo3Head->getHasJsonContent()) {
            $description = false;
            $keywords = false;
            $robots = '';

            foreach ($typo3Head->getMeta() as $metaTag) {
                $type = array_key_exists('property', $metaTag) ? 'property' : 'name';
                $name = $metaTag[$type];
                $content = $metaTag['content'];

                switch ($name) {
                    case 'description':
                        if (!$description) {
                            $this->pageConfig->setMetaData('description', $content);
                            $description = true;
                        }

                        break;
                    case 'keywords':
                        if (!$keywords) {
                            $this->pageConfig->setMetaData('keywords', $content);
                            $keywords = true;
                        }

                        break;
                    case 'abstract':
                        $this->pageConfig->setMetaData('abstract', $content);

                        break;
                    case 'robots':
                        $robots = $content;

                        break;
                }
            }

            if ($description && $keywords && $typo3Head->getTitle()) {
                $this->pageConfig->getTitle()->set(__($typo3Head->getTitle()));

                if (!empty($robots)) {
                    $this->pageConfig->setMetaData('robots', $robots);
                }
            }
        }

        return parent::_prepareLayout();
    }
}
