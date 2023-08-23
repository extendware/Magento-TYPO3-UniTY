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

use Magento\Framework\View\Element\Template;
use Magento\Widget\Block\BlockInterface;
use WebVision\Unity\Helper;
use WebVision\Unity\Model;
use WebVision\Unity\Model\Config\Source\Error\Output;

abstract class AbstractBlock extends Template implements BlockInterface
{
    protected $_dataHelper;

    protected $_TYPO3Model;

    protected $_TYPO3Helper;

    protected $_factoryHelper;

    protected $_params = [
        'page_uid',
        'column_uid',
        'element_uid',
        'special',
        'special_value',
        'entry_level',
        'exclude_uid',
        'layout',
    ];

    public function __construct(
        Template\Context $context,
        Model\TYPO3 $TYPO3Model,
        Helper\TYPO3 $TYPO3Helper,
        Helper\Data $dataHelper,
        Helper\Factory $factoryHelper,
        array $data = []
    ) {
        $this->_TYPO3Model = $TYPO3Model;
        $this->_TYPO3Helper = $TYPO3Helper;
        $this->_dataHelper = $dataHelper;
        $this->_factoryHelper = $factoryHelper;
        parent::__construct($context, $data);
    }

    protected function _toHtml()
    {
        if (! $this->getTemplate()) {
            return '';
        }

        if ($this->_dataHelper->isEnabled() && $this->_validateParameters()) {
            $typo3 = $this->_TYPO3Model
                ->load($this->getMode(), $this->_getParams());
            $this->setData('url', $typo3->getUrl());
            // fix regarding HK-1748
            // T3 contents are not fetched on product detail pages
            // TODO: Investigate the cause
            if (true || !$typo3->getHasErrors()) {
                $this->assign('content', $typo3->getContent());
            } else {
                $outputErrors = $this->_dataHelper
                    ->getDevelopmentOutputErrors();
                if ($this->hasData('output_errors')) {
                    $outputErrors = $this->getData('output_errors');
                }

                switch ($outputErrors) {
                    case Output::HTML:
                        $this->assign('content', $typo3->getError()->getMessage());

                        break;
                    case Output::COMMENT:
                        $message = $typo3->getError()->getMessage();
                        $message = str_replace(
                            ['<!--', '-->'],
                            ['<%--', '--%>'],
                            $message
                        );
                        $this->assign('content', '<!-- ' . $message . ' -->');

                        break;
                    case Output::LOG:
                    default:
                        $this->_logger->critical($typo3->getError());
                }
            }

            return parent::_toHtml();
        }

        return '';
    }

    protected function _validateParameters()
    {
        if ($this->hasData('mode')) {
            switch ($this->getDataUsingMethod('mode')) {
                case 'page':
                    return $this->_hasValidId('page_uid');
                case 'column':
                    return $this->_hasValidId('page_uid') && $this->_hasValidId('column_uid');
                case 'element':
                    $this->setData('page_uid', $this->_dataHelper->getT3Rootpage());

                    return $this->_hasValidId('element_uid');
                case 'menu':
                    return true;
            }
        }

        return false;
    }

    protected function _hasValidId($uidName)
    {
        if ($this->hasData($uidName)) {
            return intval($this->getData($uidName), 10) > 0;
        }

        return false;
    }

    protected function _getParams()
    {
        if (!$this->hasData('params')) {
            $params = [];
            foreach ($this->_params as $param) {
                if ($this->hasData($param)) {
                    $params[$param] = $this->getData($param);
                }
            }
            $this->setData('params', $params);
        }

        return $this->getData('params');
    }

    public function getCacheKeyInfo()
    {
        $defaultKeyInfo = parent::getCacheKeyInfo();

        $unityKeyInfo = [
            'language:' . $this->_dataHelper
                ->getT3CurrentLanguageId(),
            'params:' . md5(serialize($this->_getParams())),
            'identifier:' . $this->getUniqueIdentifier(),
        ];

        return array_merge($defaultKeyInfo, $unityKeyInfo);
    }

    public function getUniqueIdentifier($delimiter = '-')
    {
        $identifier = $this->getMode() . $delimiter;

        switch ($this->getMode()) {
            case 'page':
                $identifier .= $this->getPageUid();

                break;
            case 'column':
                $identifier .= $this->getPageUid() . $delimiter . $this->getColumnUid();

                break;
            case 'element':
                $identifier .= $this->getElementUid();

                break;
            case 'menu':
                $identifier .= $this->getSpecial() . 'menu';

                break;
        }

        // WVTODO make array and implode with delimiter
        return $identifier;
    }
}
