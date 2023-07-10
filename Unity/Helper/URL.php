<?php

namespace WebVision\Unity\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\DataObject;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * @method int getStoreId()
 * @method $this setStoreId(int $storeId)
 * @method \Magento\Store\Api\Data\StoreInterface getStore()
 * @method $this setStore(\Magento\Store\Api\Data\StoreInterface $store)
 * @method string getProtocol()
 * @method $this setProtocol(string $protocol)
 * @method string getDomain()
 * @method $this setDomain(string $domain)
 * @method string getBaseUrlPath()
 * @methid $this setBaseUrlPath(string $baseUrlPath)
 * @method string getStorePath()
 * @method $this setStorePath(string $storePath)
 * @method string getPath()
 * @method $this setPath(string $path)
 * @method bool getHtml()
 * @method $this setHtml(bool $html)
 * @method array getQueryParams()
 * @method $this setQueryParams(array $queryParams)
 * @method string getAnchor()
 * @method $this setAnchor(string $anchor)
 */
class URL extends DataObject
{
    protected $_encodeCache = [];

    protected $_mappings = [];

    protected $_storeManager;

    protected $_urlBuilder;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        $this->_urlBuilder = $context->getUrlBuilder();
        parent::__construct($data);
    }

    public function encode()
    {
        $hash = $this->getHash();

        if (!array_key_exists($hash, $this->_encodeCache)) {
            $path = '/' . trim($this->getPath(), '/');
            $query = $this->getFlatQueryParams();

//            $mappings = $this->_getMappings();

            // move params from query to path
//            foreach ($mappings as $magKey => $seoKey) {
//                if (array_key_exists($magKey, $query)) {
//                    $path .= '/' . urlencode($seoKey) . '/' . urlencode($query[$magKey]);
//                    unset($query[$magKey]);
//                }
//            }

            $this->setPath($path);
            $this->setQuery($query);

            $this->_encodeCache[$hash] = $this->_data;
        } else {
            $this->_data = $this->_encodeCache[$hash];
        }

        return $this;
    }

    /**
     * Removes parts from the path and adds them to the query.
     *
     * @return $this The decoded url.
     */
    public function decode()
    {
        $pathArr = explode('/', $this->getPath());
        $query = $this->getFlatQueryParams();

        $mappings = []; // array_flip($this->_getMappings());

        // move params from path to query
        $count = count($pathArr);
        for ($i = 0; $i < $count; $i++) {
            $path = urldecode($pathArr[$i]);
            if (array_key_exists($path, $mappings) && array_key_exists($i + 1, $pathArr)) {
                // unset key
                unset($pathArr[$i]);
                // decode twice to remove encoded '+'
                $query[$mappings[$path]] = urldecode(urldecode($pathArr[++$i]));
                // unset value
                unset($pathArr[$i]);
            }
        }
        $path = implode('/', $pathArr);

        $this->setPath($path);
        $this->setQuery($query);

        return $this;
    }

    public function clear()
    {
        $this->setData([]);
        $this->setQueryParams([]);
        $this->setHtml(false);

        return $this;
    }

    public function setUrl($url, $storeId = null)
    {
        try {
            $store = $this->_storeManager
                ->getStore($storeId);
        } catch (\Exception $e) {
            $store = $this->_storeManager
                ->getStore();
        }

        $this->setData('store_id', $store->getId());
        $this->setData('store', $store);
        $this->setData('protocol', $this->_removeProtocol($url));
        $baseUrl = $this->_storeManager
            ->getStore($store->getId())
            ->getBaseUrl(UrlInterface::URL_TYPE_DIRECT_LINK);
        $this->_removeProtocol($baseUrl);
        if (strpos($url, $baseUrl) === 0) {
            $url = str_replace(rtrim($baseUrl, '/'), '', $url);

            $this->setData('domain', $this->_removeDomain($baseUrl));
            $this->setData('base_url_path', trim($baseUrl, '/'));
        } else {
            $this->setData('domain', $this->_removeDomain($url));
        }

        $this->setData('store_path', $this->_removeStorePath($url));
        $this->setData('anchor', $this->_removeAnchor($url));
        $this->setData('query_params', $this->_removeQuery($url));
        $this->setData('html', $this->_removeHtml($url));
        $this->setData('path', $url);

        return $this;
    }

    public function getFlatQueryParams($filter = true, $raw = false)
    {
        $queryString = http_build_query($this->getQueryParams());
        $parameters = explode('&', $queryString);
        if ($filter) {
            $parameters = array_filter($parameters);
        }
        $parameterArray = [];

        foreach ($parameters as $parameter) {
            @list($parameterName, $parameterValue) = explode('=', $parameter);
            if ($raw) {
                $parameterArray[rawurldecode($parameterName)] = rawurldecode($parameterValue);
            } else {
                $parameterArray[urldecode($parameterName)] = urldecode($parameterValue);
            }
        }

        return $parameterArray;
    }

    public function setQuery($query = [])
    {
        $this->setQueryParams(array());
        if (is_string($query)) {
            parse_str($query, $query);
        }
        $this->addQueryParam($query);

        return $this;
    }

    public function addQueryParam($key, $value = null)
    {
        $newParams = [];

        if (is_array($key)) {
            $queryString = http_build_query($key);
            parse_str($queryString, $newParams);
        } elseif ($value !== null) {
            $newParams = [$key => $value];
        }

        $params = array_merge($this->getQueryParams(), $newParams);

        $this->setQueryParams($params);
    }

    /**
     * Fetches the store path if it should be set.
     *
     * @return $this
     */
    public function fetchStorePath()
    {
        $store = $this->getStore();

//        if () {
//            $this->setStorePath('/' . $store->getCode());
//        } else {
            $this->setStorePath('');
//        }

        return $this;
    }

    public function getFullPath()
    {
        $fullPath = '';

        if ($this->getData('path')) {
            $path = '/' . ltrim($this->getData('path'), '/');

            if ($this->getData('html')) {
                $fullPath .= rtrim($path, '/') . '.html';
            } else {
                $fullPath .= $path;
            }
        }

        return $fullPath;
    }

    public function prependPath($prepend)
    {
        $path = '/' . trim($prepend, '/') . '/' . ltrim($this->getData('path'), '/');
        $this->setData('path', $path);
    }

    public function appendPath($append)
    {
        $path = '/' . trim($this->getData('path'), '/');
        if ($path !== '/') {
            $path .= '/';
        }
        $path .= ltrim($append, '/');
        $this->setData('path', $path);
    }

    protected function _removeProtocol(&$url)
    {
$url = $url ?: '';
        preg_match('/(https?:\/\/)?(.*)/', $url, $matches);
        $url = $matches[2];

        return $matches[1];
    }

    protected function _removeDomain(&$url)
    {
        $slashPos = strpos($url, '/');
        $dotPos = strpos($url, '.');
        if ($dotPos === false) {
            return '';
        }
        // if there is no slash set it to the length of the string
        if ($slashPos === false) {
            $slashPos = strlen($url);
        }
        // if there is a dot before the first slash there is a domain
        if ($dotPos < $slashPos) {
            $domain = substr($url, 0, $slashPos);
            $url = substr($url, $slashPos);

            return $domain;
        }

        return '';
    }

    protected function _removeStorePath(&$url)
    {
//        $store = $this->getStore();
//        if ($store->getStoreInUrl()) {
//            $url = preg_replace('/^\/' . $store->getCode() . '/', '', $url, 1, $matches);
//            if ($matches) {
//                return '/' . $store->getCode();
//            }
//        }

        return '';
    }

    protected function _removeAnchor(&$url)
    {
        $anchor = '';

        if (strpos($url, '#') !== false) {
            list($url, $anchor) = explode('#', $url);
        }

        return $anchor;
    }

    protected function _removeQuery(&$url)
    {
        $query = [];

        if (strpos($url, '?') !== false) {
            list($url, $paramString) = explode('?', $url);
            // sometimes magento escapes the & we have to reverse this to work properly
            $paramString = str_replace('&amp;', '&', $paramString);
            parse_str($paramString, $query);
        }

        return $query;
    }

    protected function _removeHtml(&$url)
    {
        $hasHtml = strpos($url, '.html');
        if ($hasHtml !== false) {
            $url = substr($url, 0, $hasHtml);
        }

        return (bool)$hasHtml;
    }

//    protected function _getMappings()
//    {
//        $storeId = $this->getData('store_id');
//
//        if (!array_key_exists($storeId, $this->_mappings)) {
//            $collection = Mage::getResourceModel('webvision_unity/query_mapping_collection');
//            $collection->addFieldToSelect(array('magento_key', 'seo_key'))
//                ->addFieldToFilter('store_id', array('in' => array(0, $storeId)))
//                ->setOrder('position', Varien_Data_Collection::SORT_ORDER_ASC)
//                ->setOrder('store_id', Varien_Data_Collection::SORT_ORDER_ASC);
//
//            $mappings = array();
//            foreach ($collection->getItems() as $queryParam) {
//                $mappings[$queryParam->getMagentoKey()] = $queryParam->getSeoKey();
//            }
//
//            $this->_mappings[$storeId] = $mappings;
//        }
//
//        return $this->_mappings[$storeId];
//    }

    public function __toString()
    {
        $url = $this->getData('protocol');
        $url .= $this->getData('domain');
        if ($this->getData('base_url_path')) {
            $url .= '/' . $this->getData('base_url_path');
        }
        $url .= $this->getData('store_path');
        $url .= $this->getFullPath();
        $query = http_build_query($this->getData('query_params'));
        if ($query) {
            $url .= '?' . $query;
        }
        if ($this->getData('anchor')) {
            $url .= '#' . $this->getData('anchor');
        }

        return $url;
    }

    public function getHash()
    {
        return md5($this->serialize());
    }

    public function serialize($keys = [], $valueSeparator = '=', $fieldSeparator = ' ', $quote = '"')
    {
        $data = [];
        if (empty($keys)) {
            $keys = array_keys($this->_data);
        }

        foreach ($this->_data as $key => $value) {
            if (is_array($value) || is_object($value)) {
                continue;
            }

            if (in_array($key, $keys)) {
                $data[] = $key . $valueSeparator . $quote . $value . $quote;
            }
        }

        return implode($fieldSeparator, $data);
    }
}
