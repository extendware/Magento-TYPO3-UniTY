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

use Magento\Framework\App\Area;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\State;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    const GENERAL_ENABLED = 'webvision_unity/general/enabled';
    const MAG_WIDGET_CACHE_LIFETIME = 'webvision_unity/magento/widget_cache_lifetime';
    const MAG_PAGE_CACHE_LIFETIME = 'webvision_unity/magento/page_cache_lifetime';
    const T3_HOST = 'webvision_unity/typo3/host';
    const T3_USERNAME = 'webvision_unity/typo3/username';
    const T3_PASSWORD = 'webvision_unity/typo3/password';
    const T3_DATABASE = 'webvision_unity/typo3/database';
    const T3_PROTOCOL = 'webvision_unity/typo3/protocol';
    const T3_VERIFY_SSL = 'webvision_unity/typo3/verify_ssl';
    const T3_DOMAIN = 'webvision_unity/typo3/domain';
    const T3_OWN_DOMAIN = 'webvision_unity/typo3/own_domain';
    const T3_SUBFOLDER = 'webvision_unity/typo3/subfolder';
    const T3_SUBPAGE = 'webvision_unity/typo3/subpage';
    const T3_URL_PREFIX = 'webvision_unity/typo3/url_prefix';
    const T3_URL_EXTENSION = 'webvision_unity/typo3/url_extension';
    const T3_ENCRYPTION_KEY = 'webvision_unity/typo3/encryption_key';
    const T3_PAGE_TYPE_HEAD = 'webvision_unity/typo3/page_type_head';
    const T3_PAGE_TYPE_PAGE = 'webvision_unity/typo3/page_type_page';
    const T3_PAGE_TYPE_COLUMN = 'webvision_unity/typo3/page_type_column';
    const T3_PAGE_TYPE_ELEMENT = 'webvision_unity/typo3/page_type_element';
    const T3_PAGE_TYPE_MENU = 'webvision_unity/typo3/page_type_menu';
    const T3_PAGE_TYPE_XMLSITEMAP = 'webvision_unity/typo3/page_type_xmlsitemap';
    const T3_ROOTPAGE = 'webvision_unity/typo3/rootpage';
    const T3_PRODUCTPAGE = 'webvision_unity/typo3/productpage';
    const T3_CATEGORYPAGE = 'webvision_unity/typo3/categorypage';
    const T3_MULTILANGUAGE = 'webvision_unity/typo3/multilanguage';
    const T3_LINK_VAR = 'webvision_unity/typo3/link_var';
    const T3_DEFAULT_LANGUAGE_ID = 'webvision_unity/typo3/default_language_id';
    const T3_CURRENT_LANGUAGE_ID = 'webvision_unity/typo3/current_language_id';
    const T3_CREDENTIALS = 'webvision_unity/typo3/credentials';
    const T3_CREDENTIALS_USERNAME = 'webvision_unity/typo3/credentials_username';
    const T3_CREDENTIALS_PASSWORD = 'webvision_unity/typo3/credentials_password';
    const T3_SEND_FILES = 'webvision_unity/typo3/send_files';
    const T3_REALURL_VERSION = 'webvision_unity/typo3/realurl_version';
    const DEVELOPMENT_OUTPUT_ERRORS = 'webvision_unity/development/output_errors';
    const DEVELOPMENT_NO_CACHE = 'webvision_unity/development/no_cache';
    const DEVELOPMENT_TIMEOUT = 'webvision_unity/development/timeout';
    const DEVELOPMENT_XDEBUG_TYPO3 = 'webvision_unity/development/xdebug_typo3';
    const MODE_HEAD_WHITELIST = 'webvision_unity/mode/head_whitelist';
    const MODE_HEAD_BLACKLIST = 'webvision_unity/mode/head_blacklist';
    const MODE_HEAD_ORDER = 'webvision_unity/mode/head_order';
    const SEO_FILTERS = 'webvision_unity/seo/status_filter';
    const SEO_PAGINATION = 'webvision_unity/seo/status_pagination';

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var State
     */
    protected $state;

    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        State $state
    ) {
        $this->storeManager = $storeManager;
        $this->state = $state;
        parent::__construct($context);
    }

    /**
     * Return true if the extension is enabled, false otherwise.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::GENERAL_ENABLED, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the default cache lifetime for the widget as an int or false if it is not set or null.
     *
     * @param null|string $store
     *
     * @return bool|int
     */
    public function getMagWidgetCacheLifetime($store = null)
    {
        $value = $this->scopeConfig
            ->getValue(static::MAG_WIDGET_CACHE_LIFETIME, ScopeInterface::SCOPE_STORE, $store);
        $cacheLifetime = intval($value, 10);

        return ($cacheLifetime > 0) ? $cacheLifetime : false;
    }

    /**
     * Returns the default cache lifetime for the page as an int or false if it is not set or null.
     *
     * @param null|string $store
     *
     * @return bool|int
     */
    public function getMagPageCacheLifetime($store = null)
    {
        $value = $this->scopeConfig
            ->getValue(static::MAG_PAGE_CACHE_LIFETIME, ScopeInterface::SCOPE_STORE, $store);
        $cacheLifetime = intval($value, 10);

        return ($cacheLifetime > 0) ? $cacheLifetime : false;
    }

    /**
     * Returns the TYPO3 database host or null if not set.
     *
     * @param null|string $store
     *
     * @return string|null
     */
    public function getT3Host($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_HOST, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 database username or null if not set.
     *
     * @param null|string $store
     *
     * @return string|null
     */
    public function getT3Username($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_USERNAME, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 database password or null if not set.
     *
     * @param null|string $store
     *
     * @return string|null
     */
    public function getT3Password($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_PASSWORD, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 database name or null if not set.
     *
     * @param null|string $store
     *
     * @return string|null
     */
    public function getT3Database($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_DATABASE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the protocol to use when fetching data from TYPO3.
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getT3Protocol($store = null)
    {
        return (int)$this->scopeConfig
            ->getValue(static::T3_PROTOCOL, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns if the SSL certificate should be verified or not.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function getT3VerifySsl($store = null)
    {
        return (bool)$this->scopeConfig
            ->getValue(static::T3_VERIFY_SSL, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns if the magento domain should be used or another one to fetch data from TYPO3.
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getT3Domain($store = null)
    {
        return (int)$this->scopeConfig
            ->getValue(static::T3_DOMAIN, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the own domain TYPO3 has.
     *
     * @param null|string $store
     *
     * @return string
     */
    public function getT3OwnDomain($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_OWN_DOMAIN, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the subfolder TYPO3 is installed in.
     *
     * @param null|string $store
     *
     * @return string
     */
    public function getT3Subfolder($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_SUBFOLDER, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns if TYPO3 uses a subpage.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function getT3Subpage($store = null)
    {
        return (bool)$this->scopeConfig
            ->getValue(static::T3_SUBPAGE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 url prefix.
     *
     * @param null|string $store
     *
     * @return string
     */
    public function getT3UrlPrefix($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_URL_PREFIX, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 url extension.
     *
     * @param null|string $store
     *
     * @return string
     */
    public function getT3UrlExtension($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_URL_EXTENSION, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 encryptionKey.
     *
     * @param null|string $store
     *
     * @return string
     */
    public function getT3EncryptionKey($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_ENCRYPTION_KEY, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the pageType used for the specified mode
     *
     * @param string      $mode
     * @param null|string $store
     *
     * @return int
     */
    public function getT3PageType($mode, $store = null)
    {
        $const = 'self::T3_PAGE_TYPE_' . strtoupper($mode);

        if (defined($const)) {
            return (int)$this->scopeConfig
                ->getValue(constant($const), ScopeInterface::SCOPE_STORE, $store);
        }

        return 0;
    }

    /**
     * Returns the id of the TYPO3 rootpage or null if not set.
     *
     * @param null|string $store
     *
     * @return int|null
     */
    public function getT3Rootpage($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_ROOTPAGE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the id of the TYPO3 product page or null if not set.
     *
     * @param null|string $store
     *
     * @return int|null
     */
    public function getT3Productpage($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_PRODUCTPAGE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the id of the TYPO3 category page or null if not set.
     *
     * @param null|string $store
     *
     * @return int|null
     */
    public function getT3Categorypage($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_CATEGORYPAGE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns true if TYPO3 is multilanguage, false otherwise.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function isT3Multilanguage($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_MULTILANGUAGE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 config.linkVar for sys_language_uid
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function getT3LinkVar($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_LINK_VAR, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 default language id or null if not set.
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getT3DefaultLanguageId($store = null)
    {
        return (int)$this->scopeConfig
            ->getValue(static::T3_DEFAULT_LANGUAGE_ID, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 current language id or null if not set.
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getT3CurrentLanguageId($store = null)
    {
        return (int)$this->scopeConfig
            ->getValue(static::T3_CURRENT_LANGUAGE_ID, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Check if we are in a multilanguage environment.
     * Returns true if multilanguage is set to true and the current language id is unequal to the default language id,
     * false otherwise.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function isMultilanguage($store = null)
    {
        if ($this->isT3Multilanguage($store)) {
            if ($this->getT3DefaultLanguageId($store) > 0) {
                return true;
            }

            return $this->getT3DefaultLanguageId($store) !== $this->getT3CurrentLanguageId($store);
        }

        return false;
    }

    /**
     * Returns if TYPO3 needs credentials for .htpasswd or not.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function getT3NeedsCredentials($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_CREDENTIALS, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 .htpasswd username
     *
     * @param null|string $store
     *
     * @return string
     */
    public function getT3CredentialsUsername($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_CREDENTIALS_USERNAME, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the TYPO3 .htpasswd password
     *
     * @param null|string $store
     *
     * @return string
     */
    public function getT3CredentialsPassword($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_CREDENTIALS_PASSWORD, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns if files should be send to TYPO3.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function getT3SendFiles($store = null)
    {
        return (bool)$this->scopeConfig
            ->getValue(static::T3_SEND_FILES, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the version of realurl installed in TYPO3.
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getT3RealurlVersion($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::T3_REALURL_VERSION, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns an integer determine how to output errors.
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getDevelopmentOutputErrors($store = null)
    {
        return $this->scopeConfig
            ->getValue(static::DEVELOPMENT_OUTPUT_ERRORS, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns a boolean determine if to use no_cache parameter or not.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function getDevelopmentNoCache($store = null)
    {
        return (bool)$this->scopeConfig
            ->getValue(static::DEVELOPMENT_NO_CACHE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns an integer representing the seconds before cURL should trigger a timeout.
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getDevelopmentTimeout($store = null)
    {
        return (int)$this->scopeConfig
            ->getValue(static::DEVELOPMENT_TIMEOUT, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns a boolean determine if to send xdbug cookie to TYPO3 or not.
     *
     * @param null|string $store
     *
     * @return bool
     */
    public function getDevelopmentXdebugTypo3($store = null)
    {
        return (bool)$this->scopeConfig
            ->getValue(static::DEVELOPMENT_XDEBUG_TYPO3, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns the whitelist entries.
     *
     * @param null|string $store
     *
     * @return array
     */
    public function getModeHeadWhitelist($store = null)
    {
        $whitelist = $this->scopeConfig
            ->getValue(static::MODE_HEAD_WHITELIST, ScopeInterface::SCOPE_STORE, $store);
        $whitelist = explode("\r\n", $whitelist);

        return array_filter($whitelist);
    }

    /**
     * Returns the blacklist entries.
     *
     * @param null|string $store
     *
     * @return array
     */
    public function getModeHeadBlacklist($store = null)
    {
        $blacklist = $this->scopeConfig
            ->getValue(static::MODE_HEAD_BLACKLIST, ScopeInterface::SCOPE_STORE, $store);
        $blacklist = explode("\r\n", $blacklist);

        return array_filter($blacklist);
    }

    /**
     * Returns if the whitelist of the blacklist should be processed first.
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getModeHeadOrder($store = null)
    {
        return (int)$this->scopeConfig
            ->getValue(static::MODE_HEAD_ORDER, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns if seo friedly url should enabled for filters
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getSeoFilterStatus($store = null)
    {
        return (int)$this->scopeConfig
            ->getValue(static::SEO_FILTERS, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Returns if seo friedly url should enabled for pagination
     *
     * @param null|string $store
     *
     * @return int
     */
    public function getSeoPaginationStatus($store = null)
    {
        return (int)$this->scopeConfig
            ->getValue(static::SEO_PAGINATION, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Checks if we are on an admin page.
     *
     * @return bool
     */
    public function isAdmin()
    {
        $isAdmin = false;

        try {
            $isAdmin = $this->state
                ->getAreaCode() === Area::AREA_ADMINHTML;
        } catch (\Exception $e) { // Nothing
        }

        return $isAdmin;
    }

    public function switchMode($mode, \Closure $callback = null)
    {
        switch ($mode) {
            case 'head':
            case 'column':
            case 'element':
            case 'menu':
                $fieldName = 'page_uid';

                break;
            default:
                $fieldName = $mode . '_uid';
        }

        if ($callback === null) {
            return $fieldName;
        }

        return $callback($fieldName, $mode);
    }
}
