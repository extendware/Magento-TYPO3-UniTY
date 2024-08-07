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

use WebVision\Unity\Model\Config\Source\Error\Output;

class Page extends AbstractBlock
{
    protected $_template = 'content.phtml';

    public function _construct()
    {
        parent::_construct();

        // set defaults
        $this->setData('mode', 'page');
        if ($pageId = $this->_TYPO3Helper->getPageId(true)) {
            $this->setData('page_uid', $pageId);
        }
        if (!$this->hasData('cache_lifetime')) {
            $this->setData('cache_lifetime', $this->_dataHelper->getMagPageCacheLifetime());
        }

        $this->_checkNoCacheFlag();
    }

    /**
     * Checks if the page has no_cache flag set to 1 and sets the cache_lifetime to null to prevent caching of the
     * block.
     */
    protected function _checkNoCacheFlag()
    {
        $page = $this->_TYPO3Model
            ->load('page', ['page_uid' => $this->getData('page_uid')]);

        if ($page->getData('no_cache') === 1) {
            $this->setData('cache_lifetime');
        }
    }

    public function _prepareLayout()
    {
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

        } elseif ($typo3Head->getHasErrors()) {
            $outputErrors = $this->_dataHelper
                ->getDevelopmentOutputErrors();

            if ($this->hasData('output_errors')) {
                $outputErrors = $this->getData('output_errors');
            }

            switch ($outputErrors) {
                case Output::HTML:
                    $this->assign('content', $typo3Head->getError()->getMessage());

                    break;
                case Output::COMMENT:
                    $message = $typo3Head->getError()->getMessage();
                    $message = str_replace(
                        ['<!--', '-->'],
                        ['<%--', '--%>'],
                        $message
                    );
                    $this->assign('content', '<!-- ' . $message . ' -->');

                    break;
                case Output::LOG:
                default:
                    $this->_logger->critical($typo3Head->getError());
            }
        }

        return parent::_prepareLayout();
    }
}
