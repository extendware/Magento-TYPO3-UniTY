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
namespace WebVision\Unity\Model\ResourceModel;

use Magento\Framework\App\Request;
use Magento\Framework\App\Response;
use Magento\Framework\DataObject;
use Magento\Framework\EntityManager\EventManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use WebVision\Unity\Helper;
use WebVision\Unity\Model\Config\Source\Web\Protocol;
use WebVision\Unity\Model\Curl;
use WebVision\Unity\Model\TYPO3 as Model;

class TYPO3 extends DataObject
{
    protected $_idFieldName = 'id';

    protected $_dataHelper;

    protected $_eventManager;

    protected $_curl;

    protected $_TYPO3Helper;

    protected $_request;

    protected $_response;

    protected $_storeManager;

    protected $jsonHelper;

    public function __construct(
        Helper\Data $dataHelper,
        EventManager $eventManager,
        Helper\TYPO3 $TYPO3Helper,
        Curl $curl,
        Request\Http $request,
        Response\Http $response,
        StoreManagerInterface $storeManager,
        \Magento\Framework\Json\Helper\Data $jsonHelper
    ) {
        parent::__construct();

        $this->_dataHelper = $dataHelper;
        $this->_eventManager = $eventManager;
        $this->_TYPO3Helper = $TYPO3Helper;
        $this->_curl = $curl;
        $this->_request = $request;
        $this->_response = $response;
        $this->_storeManager = $storeManager;
        $this->jsonHelper = $jsonHelper;
    }

    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    /**
     * Load an object
     *
     * @param Model  $object
     * @param string $mode
     * @param array  $params
     *
     * @return $this
     */
    public function load(Model $object, $mode, array $params)
    {
        $this->_idFieldName = $this->_dataHelper
            ->switchMode($mode);

        try {
            $url = $this->_TYPO3Helper
                ->getFetchUrl($mode, $params);

            $eventParams = [
                'url' => new DataObject(['url' => $url]),
                'mode' => $mode,
                'params' => $params,
            ];

            $this->_eventManager
                ->dispatch('webvision_unity_fetchdata_before', $eventParams);
            $url = $eventParams['url']->getUrl();

            if (empty($url)) {
                $object->setContent('');
                $object->setHasJsonContent(false);

                return $this;
            }

            $object->setUrl($url);

            $data = $this->_fetchData($url);

            $eventParams = [
                'fetched_data' => new DataObject(['fetched_data' => $data]),
            ];

            $this->_eventManager
                ->dispatch('webvision_unity_fetchdata_after', $eventParams);
            $data = $eventParams['fetched_data']->getFetchedData();

            if (strpos(trim($data), '{') !== 0) {
                $data = $this->_fixImageUrls($data);
                $data = $this->_replaceMarkers($data);
                $data = $this->_removeUnneededT3Parameter($data);
                $object->setContent($data);
                $object->setHasJsonContent(false);
            } else {
                $data = $this->_replaceMarkers($data);
                $object->setData($this->jsonHelper->jsonDecode($data));
                $object->setHasJsonContent(true);
            }

            $object->setId($params[$this->_idFieldName]);
        } catch (\Exception $e) {
            $object->setContent('');
            $object->setHasErrors(true);
            $object->setHasJsonContent(false);
            $object->setError($e);
        }

        return $this;
    }

    public function afterLoad(Model $object)
    {
        return $this;
    }

    /**
     * Fixes the url in images and source sets.
     *
     * @param string $data
     *
     * @throws \Exception
     *
     * @return string
     */
    protected function _fixImageUrls($data)
    {
        $data = preg_replace_callback('/(<img.*?src=["\'])(.*?)(["\'][\s\/>])/', [$this, 'replaceBaseUrl'], $data);
        $data = preg_replace_callback('/(srcset=["\'])(.*?)([\'"][\s\/>])/', [$this, 'replaceBaseUrl'], $data);
        // Updates the base URL of the URL specified in "data-url" attribute from Magento 2 to TYPO3.
        $data = preg_replace_callback('/(data-url=["\'])(.*?)([\'"][\s\/>])/', [$this, 'replaceBaseUrl'], $data);

        return $data;
    }

    /**
     * This method is used as a callback for preg_replace_callback and replaces the base url marker with the typo3 url
     * marker in the second group.
     *
     * @param array $matches The groups of the matches.
     *
     * @return string
     */
    public function replaceBaseUrl($matches)
    {
        return $matches[1] . str_replace('%BASE_URL%', '%TYPO3_URL%', $matches[2]) . $matches[3];
    }

    /**
     * Replaces the TYPO3 und Magento url markers.
     *
     * @param string $data
     *
     * @return string
     */
    protected function _replaceMarkers($data)
    {
        $baseUrl = $this->_storeManager
            ->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_LINK, $this->_request->isSecure());

        if ($this->_dataHelper->getT3Subpage()) {
            $baseUrl .= $this->_dataHelper
                ->getT3UrlPrefix();
        }

        $baseUrl = rtrim($baseUrl, '/');
        $data = str_replace('%BASE_URL%', $baseUrl, $data);

        $typo3Url = $this->_TYPO3Helper->getT3BaseUrl(
            null,
            Protocol::CURRENT
        );

        $typo3Url = rtrim($typo3Url, '/');
        $data = str_replace('%TYPO3_URL%', $typo3Url, $data);

        return $data;
    }

    /**
     * Removes unneeded parameters from TYPO3 urls which will later be replaced anyways.
     *
     * @param string $data
     *
     * @return string
     */
    protected function _removeUnneededT3Parameter($data)
    {
        // kill language parmeter
        $data = preg_replace('/\?' . $this->_dataHelper->getT3LinkVar() . '=[0-9]*&amp;/', '?', $data);
        $data = preg_replace('/\?' . $this->_dataHelper->getT3LinkVar() . '=[0-9]*&/', '?', $data);
        $data = preg_replace('/(\?|&)' . $this->_dataHelper->getT3LinkVar() . '=[0-9]*/', '', $data);

        // kill cHash parameter
        $data = preg_replace('/\?cHash=[a-z0-9]*&amp;/', '?', $data);
        $data = preg_replace('/\?cHash=[a-z0-9]*&/', '?', $data);
        $data = preg_replace('/(\?|&)cHash=[a-z0-9]*/', '', $data);

        return $data;
    }

    protected function _fetchData($url)
    {
        $curl = $this->_curl;
        $timeout = $this->_dataHelper
            ->getDevelopmentTimeout();

        $curl->setTimeout(3000);
        $curl->setOption(CURLOPT_CONNECTTIMEOUT, $timeout);

        if ($this->_dataHelper->getT3NeedsCredentials()) {
            $curl->setCredentials(
                $this->_dataHelper->getT3CredentialsUsername(),
                $this->_dataHelper->getT3CredentialsPassword()
            );
        }

        if (! $this->_dataHelper->getT3VerifySsl()) {
            $curl->setOption(CURLOPT_SSL_VERIFYPEER, false);
        }

        if ($this->_dataHelper->getT3SendFiles()) {
            /** @TODO Implement! */
        }

        $curl->post($url, $this->_request->getPost()->toArray());
        $result = $curl->getBody();
        $headers = $curl->getHeaders();
        $status = $curl->getStatus();

        // if status doesn't start with a 2 or isn't 100 we didn't get our content
        if ($status !== 100 && strpos($status, '2') !== 0) {
            // if status starts with a 3 it is a redirect
            if (strpos($status, '3') === 0) {
                // Check if 'Location' key exists in the headers array
                if (isset($headers['Location'])) {
                    $location = $this->_replaceMarkers($headers['Location']);
                    return $this->_fetchData($location);
                } else {
                    // Handle the case where 'Location' is not set
                    throw new \Exception('Redirect status received, but no Location header found.');
                }
            } else {
                $message = [];
                $message[] = 'Status: ' . $status;
                $message[] = 'Url: ' . $url;
                $message[] = 'Response:';
                $message[] = $result;

                throw new \Exception(implode('<br>' . PHP_EOL, $message));
            }
        }


        return $result;
    }

    /**
     * Sets the cookies which came back from TYPO3 in Magento.
     *
     * @param string $cookieName
     * @param array $cookieData
     */
    protected function _setCookie($cookieName, $cookieData)
    {
        // @codingStandardsIgnoreStart
        $_COOKIE[$cookieName] = $cookieData['value'];
        // @codingStandardsIgnoreEnd
        $setCookieParams = [
            'name' => $cookieName,
            'value' => $cookieData['value'],
        ];
        if (array_key_exists('expires', $cookieData)) {
            $expires = new \DateTime($cookieData['expires']);
            $setCookieParams['expires'] = $expires->getTimestamp();
        } else {
            $setCookieParams['expires'] = null;
        }
        $setCookieParams['path'] = array_key_exists('path', $cookieData) ? $cookieData['path'] : null;
        $setCookieParams['domain'] = array_key_exists('domain', $cookieData) ? $cookieData['domain'] : null;
        $setCookieParams['secure'] = array_key_exists('secure', $cookieData) ? $cookieData['secure'] : null;
        $setCookieParams['httponly'] = array_key_exists('httponly', $cookieData) ? $cookieData['httponly'] : null;
        call_user_func_array('setcookie', $setCookieParams);
    }
}
