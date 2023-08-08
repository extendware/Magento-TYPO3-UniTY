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

class Menu extends AbstractBlock implements \Magento\Widget\Block\BlockInterface
{
    protected $_template = 'menu.phtml';

    public function _construct()
    {
        parent::_construct();

        // set defaults if not set via widget
        $this->setData('mode', 'menu');
        if ($pageId = $this->_TYPO3Helper->getPageId(true)) {
            $this->setData('page_uid', $pageId);
        }

        if (!$this->hasData('layout')) {
            $this->setData('layout', 'menu');
        } elseif ($this->getData('layout') == 'ownLayout' && $this->hasData('own_layout')) {
            $this->setData('layout', $this->getData('own_layout'));
        }

        if (!$this->getTemplate()) {
            $this->setTemplate('WebVision_Unity::menu.phtml');
        }

        if (!$this->hasData('cache_lifetime')) {
            $this->setData('cache_lifetime', $this->_dataHelper->getMagWidgetCacheLifetime());
        }
    }
}