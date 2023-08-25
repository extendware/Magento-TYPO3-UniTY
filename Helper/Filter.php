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
namespace WebVision\Unity\Helper;

use Magento\Catalog\Helper\Category;
use Magento\Catalog\Model\CategoryFactory;
use Magento\Catalog\Model\CategoryRepository;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory as AttributeCollection;
use Magento\Eav\Model\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\State;
use Magento\Framework\Registry;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use WebVision\Unity\Model\QueryMapping;

class Filter extends AbstractHelper
{
    const LIMITER = 'product_list_limit';

    const SORTER = 'product_list_order';

    const PAGER = 'page';

    const PRICE_ATTRIBUTE_KEY = 'price';

    const CATEGORY_URL_SUFFIX = 'catalog/seo/category_url_suffix';
    /**
     * @var StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var AttributeCollection
     */
    protected $_attributeCollection;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var categoryHelper
     */
    protected $_categoryHelper;

    /**
     * @var categoryRepository
     */
    protected $_categoryRepository;

    /**
     * @var _url
     */
    protected $_url;

    /**
     * @var _eavConfig
     */
    protected $_eavConfig;

    /**
     * @var _registry
     */
    protected $_registry;

    /**
     * @var queryMappingFactory
     */
    protected $_queryMappingFactory;

    /**
     * @var _categoryFactory
     */
    protected $_categoryFactory;

    /**
     * @var ScopeConfigInterface
     */
    protected $_scopeConfig;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        UrlInterface $url,
        CategoryRepository $categoryRepository,
        Category $categoryHelper,
        Config $eavConfig,
        Registry $registry,
        QueryMapping $queryMappingFactory,
        CategoryFactory $categoryFactory,
        AttributeCollection $attributeCollection,
        ScopeConfigInterface $scopeConfig,
        State $state
    ) {
        $this->_storeManager = $storeManager;
        $this->state = $state;
        $this->_url = $url;
        $this->_categoryRepository = $categoryRepository;
        $this->_categoryHelper = $categoryHelper;
        $this->_eavConfig = $eavConfig;
        $this->_registry = $registry;
        $this->_queryMappingFactory = $queryMappingFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_attributeCollection = $attributeCollection;
        $this->_scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Gte Config Value
     *
     * @param mixed $config_path
     *
     * @return string
     */
    public function getConfig($config_path)
    {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;

        return $this->scopeConfig->getValue($config_path, $storeScope);
    }

    /**
     * Find Current Store Id
     *
     * @return int storeId
     */
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * Return Query Mapping collection
     *
     * @return Collection|array
     */
    public function getQueryMappingCollection()
    {
        $collection = $this->_queryMappingFactory->getCollection();

        return $collection;
    }

    /**
     * Return Current page URL
     *
     * @return Url|string
     */
    public function getCurrentUrl()
    {
        $url = $this->_url->getCurrentUrl();

        return strtok($url, '?');
    }

    /**
     * Return current category URL
     *
     * @return URL|string
     */
    public function getCurrentCategoryUrl()
    {
        $catObj = $this->_registry->registry('current_category');
        if ($catObj && $catObj->getId()) {
            return $this->getCategoryUrl($catObj->getId());
        }

        return false;
    }

    /**
     * Return category URL from category ID
     *
     * @param int $categoryId
     *
     * @return Url|string
     */
    public function getCategoryUrl($categoryId)
    {
        $categoryObj = $this->_categoryRepository->get($categoryId);

        return $this->_categoryHelper->getCategoryUrl($categoryObj);
    }

    /**
     * Return product attribute ID from value
     *
     * @param string $attributeLabel
     * @param int $attributeValue
     *
     * @return attribeVal|string
     */
    public function getAttributeIdfromText($attributeLabel, $attributeValue)
    {
        $attribute = $this->_eavConfig->getAttribute('catalog_product', $attributeLabel);

        return $attribeVal = $attribute->getSource()->getOptiontext($attributeValue);
    }

    /**
     * Return category suffix
     *
     * @return categorySuffix|string
     */
    public function getCategorySuffix()
    {
        return $this->getConfig(self::CATEGORY_URL_SUFFIX);
    }

    /**
     * Generate layered navigation filter URL for category page
     *
     * @param string $categoryUrl
     * @param string $attributeLabel
     * @param string $attribeVal
     *
     * @return url|string
     */
    public function generateFilterUrl($categoryUrl, $attributeLabel, $attribeVal)
    {
        $categoryUrlSuffix = $this->getCategorySuffix();

        //clear pager from URL
        $categoryUrl = $this->clearPagerFilterFromUrl($categoryUrl);
        //clear pager from url end

        if ($this->getSeoKey($attribeVal)) {
            $attribeVal = $this->getSeoKey($attribeVal);
        }

        $attribeVal = strtolower($attribeVal);

        //price attribute customization
        if ($attributeLabel == self::PRICE_ATTRIBUTE_KEY) {
            $attribeVal = 'price_' . $attribeVal;
            $categoryUrl = $this->clearPriceFilterFromUrl($categoryUrl);

            return trim(str_replace(['.html'], '', $categoryUrl)) . $attribeVal . $categoryUrlSuffix;
        }
        //price attribute customization end

        $finalUrl = trim(str_replace(['.html'], '', $categoryUrl)) . $attribeVal;

        //ordering customization start
        /*$urlKey_explode = explode('/', $finalUrl);
        $filterExist = $this->checkFilterExistInUrl($urlKey_explode);
        if($filterExist){
            $filterDetails = $this->getFilterDetailsForLabel($urlKey_explode);
            if(count($filterDetails) > 1){
                $position = [];
                foreach ($filterDetails as $key => $value) {
                    if ($this->getSeoKey($value)) {
                        $value = $this->getSeoKey($value);
                    }
                    $categoryUrl = trim(str_replace("/".$value."/", '/', $categoryUrl));
                    $positionRank = $this->getPositionKey($key);
                    if($key != self::PRICE_ATTRIBUTE_KEY){
                        $position[$positionRank] = $value;
                    }
                }
                ksort($position);
                $urlAppend = implode("/", $position);
                return $categoryUrl.$urlAppend.$categoryUrlSuffix;
            }
        }*/
        //ordering customization end

        $path = $finalUrl . $categoryUrlSuffix;

        return $path;
    }

    /**
     * Clear Price Filter from URL
     *
     * @param string $categoryUrl
     *
     * @return url|string
     */
    public function clearPriceFilterFromUrl($categoryUrl)
    {
        $urlParts = parse_url($categoryUrl);
        $pathArray = explode('/', $urlParts['path']);
        foreach ($pathArray as $key => $pathSingle) {
            $attVal = $pathSingle;
            $priceKey = strtok($attVal, '_');
            if ($priceKey && $priceKey == self::PRICE_ATTRIBUTE_KEY) {
                return $categoryUrl = trim(str_replace(['/' . $attVal], '', $categoryUrl));
            }
        }

        return $categoryUrl;
    }

    /**
     * Clear Pager Filter from URL
     *
     * @param string $categoryUrl
     * @param mixed $url
     *
     * @return url|string
     */
    public function clearPagerFilterFromUrl($url)
    {
        $urlParts = parse_url($url);
        $pathArray = array_filter(explode('/', $urlParts['path']));
        $valueToRemove = '';
        $finalRemoveUrlPart = '';

        foreach ($pathArray as $key => $pathSingle) {
            $attVal = $pathSingle;
            if ($this->getMagentoKey($attVal)) {
                $attMagentoVal = $this->getMagentoKey($attVal);
                $attValArray = explode('/', $attMagentoVal);
                if (isset($attValArray[1]) && $attValArray[0] == self::PAGER) {
                    $finalRemoveUrlPart = '/' . $attVal . '/';
                }
            } else {
                if ($attVal == self::PAGER && isset($pathArray[$key + 1])) {
                    $valueToRemove = $pathArray[$key + 1];
                    $finalRemoveUrlPart = '/' . $attVal . '/' . $valueToRemove . '/';
                }
            }
        }

        $cleanedUrl = trim(str_replace([$finalRemoveUrlPart], '/', $url));

        if (substr($cleanedUrl, -1) != '/') {
            $cleanedUrl = $cleanedUrl . '/';
        }

        return $cleanedUrl;
    }

    /**
     * Generate sorter URL for category page
     *
     * @param string $categoryUrl
     * @param string $attributeLabel
     * @param string $attribeVal
     *
     * @return url|string
     */
    public function generateSorterUrl($categoryUrl, $attributeLabel, $attribeVal)
    {
        $urlParts = parse_url($categoryUrl);
        $pathArray = explode('/', $urlParts['path']);
        $sorterKey = '';
        $sorterValue = '';
        $categoryUrlSuffix = $this->getConfig(self::CATEGORY_URL_SUFFIX);

        foreach ($pathArray as $key => $pathSingle) {
            $attVal = $pathSingle;
            if ($attVal == self::SORTER) {
                $sorterKey = $pathSingle;
                if (isset($pathArray[$key + 1])) {
                    $sorterValue = $pathArray[$key + 1];
                }
            }
        }

        if ($sorterKey) {
            $categoryUrl = str_replace(['/' . $sorterKey, '/' . $sorterValue], '', $categoryUrl);
        }

        $path = trim(str_replace(['.html'], ' ', $categoryUrl)) . $attributeLabel . '/' . $attribeVal . $categoryUrlSuffix;

        return $path;
    }

    /**
     * Generate Limiter URL for category page
     *
     * @param string $categoryUrl
     * @param string $attributeLabel
     * @param string $attribeVal
     *
     * @return url|string
     */
    public function generateLimiterUrl($categoryUrl, $attributeLabel, $attribeVal)
    {
        $urlParts = parse_url($categoryUrl);
        $pathArray = explode('/', $urlParts['path']);
        $limiterKey = '';
        $limiterValue = '';
        $categoryUrlSuffix = $this->getConfig(self::CATEGORY_URL_SUFFIX);

        foreach ($pathArray as $key => $pathSingle) {
            $attVal = $pathSingle;
            if ($attVal == self::LIMITER) {
                $limiterKey = $pathSingle;
                if (isset($pathArray[$key + 1])) {
                    $limiterValue = $pathArray[$key + 1];
                }
            }
        }

        if ($limiterKey) {
            $categoryUrl = str_replace(['/' . $limiterKey, '/' . $limiterValue], '', $categoryUrl);
        }

        $path = trim(str_replace(['.html'], ' ', $categoryUrl)) . $attributeLabel . '/' . $attribeVal . $categoryUrlSuffix;

        return $path;
    }

    /**
     * Generate Pager URL for category page
     *
     * @param string $categoryUrl
     * @param string $attributeLabel
     * @param string $attribeVal
     *
     * @return url|string
     */
    public function generatePagerUrl($categoryUrl, $attributeLabel, $attribeVal)
    {
        $categoryUrlSuffix = $this->getCategorySuffix();
        if ($categoryUrlSuffix) {
            $categoryUrl = $this->right_trim($categoryUrl, $categoryUrlSuffix);
        }
        $urlParts = parse_url($categoryUrl);
        $pathArray = explode('/', $urlParts['path']);
        $pageKey = '';
        $pageValue = '';

        foreach ($pathArray as $key => $pathSingle) {
            $attVal = $pathSingle;

            if ($attVal == self::PAGER) {
                $pageKey = $pathSingle;
                $pageValue = $pathArray[$key + 1];
                $finalReplace = '/' . $pageKey . '/' . $pageValue;
            }

            if ($this->getMagentoKey($pathSingle)) {
                if (strpos($this->getMagentoKey($pathSingle), self::PAGER) === 0) {
                    $pageKey = $pathSingle;
                    $finalReplace = $pageKey;
                }
            }
        }

        if ($pageKey) {
            $categoryUrl = str_replace([$finalReplace], '', $categoryUrl);
        }

        $finalKey = $attributeLabel . '/' . $attribeVal;
        if ($this->getSeoKey($finalKey)) {
            $finalKey = $this->getSeoKey($finalKey);
        }

        $finalUrl = $categoryUrl . '/' . $finalKey . $categoryUrlSuffix;

        return $finalUrl;
    }

    /**
     * get SEO key from query mapping grid
     *
     * @param string $attributeLabel
     *
     * @return seoKey|string
     */
    public function getSeoKey($attributeLabel)
    {
        $collection = $this->getQueryMappingCollection()
                           ->addFieldToFilter('magento_key', $attributeLabel)
                           ->addFieldToFilter('store_id', $this->getStoreId())
                           ->getFirstItem();
        if (count($collection->getdata()) > 0) {
            return $collection->getSeoKey();
        }

        return false;
    }

    /**
     * get Magento key from query mapping grid
     *
     * @param string $attributeLabel
     *
     * @return MagentoKey|string
     */
    public function getMagentoKey($attributeLabel)
    {
        $collection = $this->getQueryMappingCollection()
                           ->addFieldToFilter('seo_key', $attributeLabel)
                           ->addFieldToFilter('store_id', $this->getStoreId())
                           ->getFirstItem();
        if (count($collection->getdata()) > 0) {
            return $collection->getMagentoKey();
        }

        return false;
    }

    /**
     * get Pisition key from query mapping grid
     *
     * @param string $attributeLabel
     *
     * @return MagentoKey|string
     */
    public function getPositionKey($attributeLabel)
    {
        $collection = $this->getQueryMappingCollection()
                           ->addFieldToFilter('magento_key', $attributeLabel)
                           ->addFieldToFilter('store_id', $this->getStoreId())
                           ->getFirstItem();
        if (count($collection->getdata()) > 0) {
            return $collection->getPosition();
        }

        return 9999;
    }

    /**
     * get Attribute From OptionName
     *
     * @param string $urlParts
     * @param mixed $optionname
     *
     * @return string
     */
    public function getAttributeFromOptionName($optionname)
    {
        $groupAttributesCollection = $this->_attributeCollection->create()
                ->addVisibleFilter()
                ->addFieldToFilter('is_filterable', ['eq' => 1])
                ->load();
        $final = [];
        foreach ($groupAttributesCollection->getItems() as $attribute) {
            $opte = $attribute->getname();

            $attribute = $this->_eavConfig->getAttribute('catalog_product', $attribute->getData('attribute_code'));
            $options = $attribute->getSource()->getAllOptions();
            if (!empty($options)) {
                foreach ($options as $key => $op) {
                    $label = strtolower(trim($op['label']));
                    if ($label) {
                        $final[$label] = $attribute->getData('attribute_code');
                    }
                }
            }
        }
        if (array_key_exists($optionname, $final)) {
            return $final[$optionname];
        }

        return false;
    }

    /**
     * Check if filter exist in requested route
     *
     * @param string $urlParts
     *
     * @return bool
     */
    public function checkFilterExistInUrl($urlParts)
    {
        foreach (array_reverse($urlParts) as $urlpart) {
            if (!$this->checkifUrlpartIsCategory($urlpart)) {
                $attVal = urldecode($urlpart);
                if ($this->getMagentoKey($attVal)) {
                    $attVal = $this->getMagentoKey($attVal);
                }
                $attributeLbl = $this->getAttributeFromOptionName($attVal);
                if ($attributeLbl) {
                    $attr = $this->_eavConfig->getAttribute('catalog_product', $attributeLbl);
                    if ($attr && $attr->getId()) {
                        return true;
                    }
                }

                //price customization start
                $priceKey = strtok($attVal, '_');
                if ($priceKey && $priceKey == self::PRICE_ATTRIBUTE_KEY) {
                    return true;
                }
                //price customization end
            }
        }

        return false;
    }

    /**
     * Check if pager exist in requested route
     *
     * @param string $urlParts
     *
     * @return bool
     */
    public function checkpagerExistInUrl($urlParts)
    {
        foreach (array_reverse($urlParts) as $urlpart) {
            if (!$this->checkifUrlpartIsCategory($urlpart)) {
                if ($this->getMagentoKey($urlpart)) {
                    $urlpart = $this->getMagentoKey($urlpart);
                    $urlpart = strtok($urlpart, '/');
                }

                $attVal = $urlpart;

                if ($attVal == self::PAGER) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if sorter exist in requested route
     *
     * @param string $urlParts
     *
     * @return bool
     */
    public function checksorterExistInUrl($urlParts)
    {
        foreach ($urlParts as $urlpart) {
            if ($this->getMagentoKey($urlpart)) {
                $urlpart = $this->getMagentoKey($urlpart);
            }

            $attVal = $urlpart;

            if ($attVal == self::SORTER) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if limiter exist in requested route
     *
     * @param string $urlParts
     *
     * @return bool
     */
    public function checklimiterExistInUrl($urlParts)
    {
        foreach ($urlParts as $urlpart) {
            if ($this->getMagentoKey($urlpart)) {
                $urlpart = $this->getMagentoKey($urlpart);
            }

            $attVal = $urlpart;

            if ($attVal == self::LIMITER) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get category details in requested route
     *
     * @param string $urlParts
     *
     * @return categoryDetails|array
     */
    public function getCategoryDetails($urlParts)
    {
        $out = [];
        $catUrlKey = '';
        foreach (array_reverse($urlParts) as $urlpart) {
            if ($this->getMagentoKey($urlpart)) {
                $urlpart = $this->getMagentoKey($urlpart);
            }

            $categories = $this->_categoryFactory->create()
            ->getCollection()
            ->addAttributeToFilter('url_key', $urlpart)
            ->addAttributeToFilter('is_active', '1')
            ->addAttributeToSelect(['entity_id, name']);

            if ($categories->getSize()) {
                $out[] = $urlpart;
                $out[] = $categories->getFirstItem()->getEntityId();

                break;
            }
        }

        return $out;
    }

    /**
     * check if Url part Is Category
     *
     * @param string $urlParts
     * @param mixed $urlPart
     *
     * @return bool
     */
    public function checkifUrlpartIsCategory($urlPart)
    {
        $category = $this->_categoryFactory->create()
            ->getCollection()
            ->addAttributeToFilter('url_key', $urlPart)
            ->addAttributeToFilter('is_active', '1')
            ->addAttributeToSelect(['entity_id, name']);

        if ($category->getSize()) {
            return true;
        }

        return false;
    }

    /**
     * get Attribute ID from Attribute lable OptionName
     *
     * @param string $optionLabel
     * @param object $attribute
     *
     * @return string
     */
    public function getAttributeOptionIdFromOptionName($optionLabel, $attribute)
    {
        $options = $attribute->getSource()->getAllOptions();
        foreach ((array) $options as $option) {
            if (isset($option['label']) && $optionLabel == strtolower(trim($option['label']))) {
                return $option['value'];
            }
        }

        return false;
    }

    /**
     * Get Filter details in requested route
     *
     * @param string $urlParts
     *
     * @return filterDetails|array
     */
    public function getFilterDetails($urlParts)
    {
        $out = [];
        $catUrlKey = '';

        foreach ($urlParts as $key => $urlpart) {
            $outInner = [];
            if (!$this->checkifUrlpartIsCategory($urlpart)) {
                $attVal = urldecode($urlpart);
                if ($this->getMagentoKey($attVal)) {
                    $attVal = $this->getMagentoKey($attVal);
                }
                $attributeLbl = $this->getAttributeFromOptionName($attVal);
                if ($attributeLbl) {
                    $attr = $this->_eavConfig->getAttribute('catalog_product', $attributeLbl);
                    if ($attr && $attr->getId()) {
                        $attributeLabel = $attributeLbl;
                        $attributevalue = $attVal;
                        if ($attributeLabel != self::PRICE_ATTRIBUTE_KEY) {
                            $attribute = $this->_eavConfig->getAttribute('catalog_product', $attributeLabel);
                            $attributevalue = $this->getAttributeOptionIdFromOptionName($attributevalue, $attribute);
                        }
                        $out[$attributeLabel] = $attributevalue;
                    }
                }

                //price customization start
                $priceKey = strtok($attVal, '_');
                if ($priceKey && $priceKey == self::PRICE_ATTRIBUTE_KEY) {
                    $priceValue = substr(strrchr($attVal, '_'), 1);
                    $out[$priceKey] = $priceValue;
                }
                //price customization end
            }
        }

        return $out;
    }

    /**
     * Get Filter details in requested route
     *
     * @param string $urlParts
     *
     * @return filterDetails|array
     */
    public function getFilterDetailsForLabel($urlParts)
    {
        $out = [];
        $catUrlKey = '';

        foreach ($urlParts as $key => $urlpart) {
            $outInner = [];
            if (!$this->checkifUrlpartIsCategory($urlpart)) {
                $attVal = $urlpart;
                if ($this->getMagentoKey($attVal)) {
                    $attVal = $this->getMagentoKey($attVal);
                }
                $attributeLbl = $this->getAttributeFromOptionName($attVal);
                if ($attributeLbl) {
                    $attr = $this->_eavConfig->getAttribute('catalog_product', $attributeLbl);
                    if ($attr && $attr->getId()) {
                        $attributeLabel = $attributeLbl;
                        $attributevalue = $attVal;
                        if ($attributeLabel != self::PRICE_ATTRIBUTE_KEY) {
                            $attribute = $this->_eavConfig->getAttribute('catalog_product', $attributeLabel);
                        }
                        $out[$attributeLabel] = $attributevalue;
                    }
                }

                //price customization start
                $priceKey = strtok($attVal, '_');
                if ($priceKey && $priceKey == self::PRICE_ATTRIBUTE_KEY) {
                    $priceValue = substr(strrchr($attVal, '_'), 1);
                    $out[$priceKey] = $priceValue;
                }
                //price customization end
            }
        }

        return $out;
    }

    /**
     * Get Pager details in requested route
     *
     * @param string $urlParts
     *
     * @return pagerDetails|string
     */
    public function getPagerDetails($urlParts)
    {
        $out = '';
        foreach ($urlParts as $key => $urlpart) {
            $outInner = [];
            if (!$this->checkifUrlpartIsCategory($urlpart)) {
                if ($this->getMagentoKey($urlpart)) {
                    $urlpart = $this->getMagentoKey($urlpart);
                    $urlpartArray = explode('/', $urlpart);
                    if (isset($urlpartArray[0])) {
                        if ($urlpartArray[0] == self::PAGER) {
                            $attributevalue = $urlpartArray[1];
                            if ($attributevalue) {
                                return $attributevalue;
                            }
                        }
                    }
                }

                $pageVal = $urlpart;
                if ($pageVal == self::PAGER) {
                    $attributevalue = $urlParts[$key + 1];
                    if ($attributevalue) {
                        $out = $attributevalue;
                    }
                }
            }
        }

        return $out;
    }

    /**
     * Get Sorter details in requested route
     *
     * @param string $urlParts
     *
     * @return pagerDetails|string
     */
    public function getSorterDetails($urlParts)
    {
        $out = '';
        foreach ($urlParts as $key => $urlpart) {
            $outInner = [];

            $pageVal = $urlpart;
            if ($pageVal == self::SORTER) {
                if (isset($urlParts[$key + 1])) {
                    $out = $urlParts[$key + 1];
                }
            }
        }

        return $out;
    }

    /**
     * Get Limiter details in requested route
     *
     * @param string $urlParts
     *
     * @return pagerDetails|string
     */
    public function getLimiterDetails($urlParts)
    {
        $out = '';
        foreach ($urlParts as $key => $urlpart) {
            $outInner = [];

            $pageVal = $urlpart;
            if ($pageVal == self::LIMITER) {
                if (isset($urlParts[$key + 1])) {
                    $out = $urlParts[$key + 1];
                }
            }
        }

        return $out;
    }

    /**
     * right trim
     *
     * @param string $haystack
     * @param string $needle
     *
     * @return pagerDetails|string
     */
    public function right_trim(string $haystack, string $needle): string
    {
        $needle_length = strlen($needle);
        if (substr($haystack, -$needle_length) === $needle) {
            return substr($haystack, 0, -$needle_length);
        }

        return $haystack;
    }
}
