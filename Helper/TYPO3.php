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
namespace WebVision\Unity\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use WebVision\Unity\Model\Config\Source\Web\Domain;
use WebVision\Unity\Model\Config\Source\Web\Protocol;
use WebVision\Unity\Model\TYPO3\Pages;
use WebVision\Unity\Model\TYPO3\Ttcontent;

class TYPO3 extends AbstractHelper
{
    protected $_objectManager;

    protected $_storeManager;

    protected $_dataHelper;

    protected $_urlHelper;

    protected $_factoryHelper;

    protected $_registry;

    protected $_pageId = false;

    protected $_pagesModel;

    protected $_ttcontentModel;

    protected $_params = [
        'head' => [
            'currentParams' => 0,
        ],
        'page' => [
            'currentParams' => 0,
        ],
        'column' => [
            'column_uid' => 'colPos',
            'currentParams' => 0,
        ],
        'element' => [
            'element_uid' => 'uid',
            'currentParams' => 0,
        ],
        'menu' => [
            'special' => 0,
            'special_value' => 'special-value',
            'entry_level' => 'entry-level',
            'exclude_uid' => 'exclude-uid-list',
            'layout' => 0,
        ],
        'xmlsitemap' => [],
    ];

    public function __construct(
        Context $context,
        Data $dataHelper,
        URL $urlHelper,
        Registry $registry,
        Factory $factoryHelper,
        StoreManagerInterface $storeManager,
        Pages $pagesModel,
        Ttcontent $ttcontentModel
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_urlHelper = $urlHelper;
        $this->_factoryHelper = $factoryHelper;
        $this->_registry = $registry;
        $this->_storeManager = $storeManager;
        $this->_pagesModel = $pagesModel;
        $this->_ttcontentModel = $ttcontentModel;
        parent::__construct($context);
    }

    public function getPageId($fallbackToRoot = false)
    {
        $pageId = 0;
        if ($this->_dataHelper->isEnabled()) {
            $url = $this->_urlHelper->clear();

            if (!$url->getPath()) {
                $url->setUrl($this->_request->getRequestString());
            }
            $url->setHtml(false);

            if ($this->_dataHelper->getT3Subpage()) {
                $url->prependPath($this->_dataHelper->getT3UrlPrefix());
            }

            $url = strtok($url, '?');
            $page = $this->_factoryHelper->getTypo3PagesModel()->loadByPath($url);
            $pageId = (int)$page->getId();
        }
        $this->_pageId = $pageId;

        return $pageId;
    }

    public function getPageUrl($fallbackToRoot = false)
    {
        $pageURL = null;
        if ($this->_dataHelper->isEnabled()) {
            $url = $this->_urlHelper->clear();

            if (!$url->getPath()) {
                $url->setUrl($this->_request->getRequestString());
            }
            $url->setHtml(false);

            if ($this->_dataHelper->getT3Subpage()) {
                $url->prependPath($this->_dataHelper->getT3UrlPrefix());
            }

            $url = strtok($url, '?');
            $pageURL = trim($url);
        }
        $this->_pageURL = $pageURL;
        return $pageURL;
    }

    public function getT3BaseUrl($store = null, $protocol = null, $path = '', array $params = [])
    {
        $this->_urlHelper->clear();

        if ($protocol === null) {
            $protocol = $this->_dataHelper
                ->getT3Protocol($store);
        }

        switch ($protocol) {
            case Protocol::HTTP:
                $this->_urlHelper->setProtocol('http://');

                break;
            case Protocol::HTTPS:
                $this->_urlHelper->setProtocol('https://');

                break;
            default:
            case Protocol::CURRENT:
                $this->_urlHelper->setProtocol($this->_request->isSecure() ? 'https://' : 'http://');

                break;
        }

        $domain = $this->_dataHelper
            ->getT3Domain($store);

        switch ($domain) {
            case Domain::OWN:
                $this->_urlHelper->setDomain($this->_dataHelper->getT3OwnDomain($store));

                break;
            case Domain::MAGENTO:
                $domain = $this->_storeManager
                    ->getStore()
                    ->getBaseUrl();
                $this->_urlHelper->setDomain(preg_replace('/https?:\/\/(.*?)\/?$/', '$1', $domain));

                break;
        }

        $subfolder = trim($this->_dataHelper->getT3Subfolder($store) ?: '', '/');

        if ($subfolder) {
            $this->_urlHelper->setPath('/' . $subfolder . '/');
        }

        if ($path) {
            $this->_urlHelper
                ->setPath($path);
            $this->_urlHelper
                ->setQueryParams($params);
        }

        return '' . $this->_urlHelper;
    }

    public function getFetchUrl($mode, $blockParams)
    {
        if (!$this->_isContentAvailable($mode, $blockParams)) {
            return '';
        }

        $storeId = $this->_storeManager
            ->getStore(array_key_exists('store_id', $blockParams) ? $blockParams['store_id'] : null)
            ->getId();

        $url = $this->getPageUrl();

        if($mode == 'menu') {
            $this->_urlHelper
                ->setUrl($this->getT3BaseUrl($storeId), $storeId);
        } else {
            if($this->_dataHelper->getT3UrlExtension()){
                $this->_urlHelper->setUrl($this->getT3BaseUrl($storeId), $storeId)->appendPath($url.$this->_dataHelper->getT3UrlExtension());
            } else {
                $this->_urlHelper->setUrl($this->getT3BaseUrl($storeId), $storeId)->appendPath($url);
            }
        }

        $this->_urlHelper
            ->addQueryParam('type', $this->_dataHelper->getT3PageType($mode, $storeId));

        if ($this->_dataHelper->isMultilanguage($storeId)) {
            $this->_urlHelper
                ->addQueryParam(
                    $this->_dataHelper->getT3LinkVar($storeId),
                    $this->_dataHelper->getT3CurrentLanguageId($storeId)
                );
        }

        foreach ($this->_params[$mode] as $key => $paramKey) {
            switch ($key) {
                case 'currentParams':
                    $queryParams = $this->_request
                        ->getQuery();

                    $this->_urlHelper
                        ->addQueryParam($queryParams);

                    break;
                default:
                    if (array_key_exists($key, $blockParams)) {
                        if ($paramKey !== 0) {
                            $this->_urlHelper
                                ->addQueryParam($paramKey, $blockParams[$key]);
                        } else {
                            $this->_urlHelper
                                ->addQueryParam($key, $blockParams[$key]);
                        }
                    }
            }
        }

        if ($this->_dataHelper->getDevelopmentNoCache()) {
            $this->_urlHelper
                ->addQueryParam('no_cache', '1');
        }

        return '' . $this->_urlHelper;
    }

    protected function _isContentAvailable($mode, $params)
    {
        // WVTODO Feature:contentfallback
        // page_uid is the uid of the pages entry even on translation
        // we should check uid of pages_language_overlay first and if fallback says so the pages entry
        // element_uid is the specific element, no translation possible so we should add this
        switch ($mode) {
            case 'page':
            case 'menu':
                return $this->_pagesModel
                    ->isPresent($params['page_uid']);
            case 'column':
                return $this->_ttcontentModel
                    ->isColumnPresent($params['page_uid'], $params['column_uid']);
            case 'element':
                return $this->_ttcontentModel
                    ->isElementPresent($params['element_uid']);
            case 'head':
            case 'xmlsitemap':
                return true;
            default:
                return false;
        }
    }
}
