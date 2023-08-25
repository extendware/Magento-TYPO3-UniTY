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
namespace WebVision\Unity\Controller;

use Magento\Framework\App\Action\Redirect;
use Magento\Framework\App\ActionFactory;
use Magento\Framework\App\ActionInterface;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\App\Response\Http as HttpResponse;
use Magento\Framework\UrlInterface;
use Magento\UrlRewrite\Controller\Adminhtml\Url\Rewrite;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use WebVision\Unity\Helper\Data;
use WebVision\Unity\Helper\Filter;

/**
 * UrlRewrite Controller Router
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class CustomRouter implements \Magento\Framework\App\RouterInterface
{
    /**
     * Permanent redirect code
     */
    const PERMANENT_REDIRECT = 301;

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * @var UrlInterface
     */
    protected $url;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\ResponseInterface|HttpResponse
     */
    protected $response;

    /**
     * @var \Magento\UrlRewrite\Model\UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @var \WebVision\Unity\Helper\Filter
     */
    protected $_filterHelper;

    /**
     * @var \WebVision\Unity\Helper\Data
     */
    protected $_unityHelper;

    /**
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param UrlInterface $url
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param UrlFinderInterface $urlFinder
     * @param \WebVision\Unity\Helper\Filter $filterHelper
     * @param \WebVision\Unity\Helper\Data $unityHelper
     */
    public function __construct(
        ActionFactory $actionFactory,
        UrlInterface $url,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\ResponseInterface $response,
        Filter $filterHelper,
        Data $unityHelper,
        UrlFinderInterface $urlFinder
    ) {
        $this->actionFactory = $actionFactory;
        $this->url = $url;
        $this->storeManager = $storeManager;
        $this->response = $response;
        $this->urlFinder = $urlFinder;
        $this->_filterHelper = $filterHelper;
        $this->_unityHelper = $unityHelper;
    }

    /**
     * Match corresponding URL Rewrite and modify request
     *
     * @param \Magento\Framework\App\RequestInterface|HttpRequest $request
     *
     * @return ActionInterface|null
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        //custom code
        if ($this->_unityHelper->getSeoFilterStatus() || $this->_unityHelper->getSeoPaginationStatus()) {
            if ($request->getModuleName() === 'catalog' || $request->getModuleName() === 'cms') {
                return;
            }
            $identifier = $request->getPathInfo();

            if ($this->_filterHelper->getCategorySuffix()) {
                $identifier = $this->_filterHelper->right_trim($request->getPathInfo(), $this->_filterHelper->getCategorySuffix());
            }

            if ($identifier) {
                $urlKey_explode = explode('/', $identifier);
                $checkFilterExistInUrl = $this->_filterHelper->checkFilterExistInUrl($urlKey_explode);
                $checkpagerExistInUrl = $this->_filterHelper->checkpagerExistInUrl($urlKey_explode);
                $checksorterExistInUrl = $this->_filterHelper->checksorterExistInUrl($urlKey_explode);
                $checkLimiterExistInUrl = $this->_filterHelper->checklimiterExistInUrl($urlKey_explode);
                $checkCategoryExistInUrl = $this->_filterHelper->getCategoryDetails($urlKey_explode);

                if (isset($checkCategoryExistInUrl[1])) {
                    if ($checkFilterExistInUrl || $checkpagerExistInUrl || $checksorterExistInUrl || $checkLimiterExistInUrl) {
                        $request->setModuleName('catalog')->setControllerName('category')->setActionName('view');

                        //cleanup category and set catery into route
                        if (!empty($catDetails = $this->_filterHelper->getCategoryDetails($urlKey_explode))) {
                            $catid = $catDetails[1];
                            $request->setParam('id', $catid);
                            $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $catDetails[0]);
                        }

                        //cleanup filters and set filter into route
                        if (!empty($filterDetails = $this->_filterHelper->getFilterDetails($urlKey_explode))) {
                            foreach ($filterDetails as $attributeLab => $attributeVal) {
                                $request->setParam($attributeLab, $attributeVal);
                            }
                        }

                        //cleanup pager and set pager into route
                        if (!empty($pagerDetails = $this->_filterHelper->getPagerDetails($urlKey_explode))) {
                            $request->setParam('p', $pagerDetails);
                        }

                        //cleanup sorter and set sorter into route
                        if (!empty($sorterDetails = $this->_filterHelper->getSorterDetails($urlKey_explode))) {
                            $request->setParam(Filter::SORTER, $sorterDetails);
                        }

                        //cleanup limiter and set limiter into route
                        if (!empty($limiterDetails = $this->_filterHelper->getLimiterDetails($urlKey_explode))) {
                            $request->setParam(Filter::LIMITER, $limiterDetails);
                        }

                        //trailing slash customization
                        /*$lastCharacterOfUrl = substr($request->getPathInfo(), -1);
                        if($lastCharacterOfUrl !== "/"){
                            $targetUrl = ltrim($request->getPathInfo(), '/');
                            $target = $this->url->getUrl('', ['_direct' => $targetUrl])."/";
                            $this->redirect($request, $target, self::PERMANENT_REDIRECT);
                        }*/
                        //trailing slash customization end

                        return $this->actionFactory->create(
                            'Magento\Framework\App\Action\Forward',
                            ['request' => $request]
                        );
                    }
                }
            }
        }
        //custom code done

        $rewrite = $this->getRewrite(
            $request->getPathInfo(),
            $this->storeManager->getStore()->getId()
        );
        if ($rewrite === null) {
            //No rewrite rule matching current URl found, continuing with
            //processing of this URL.
            return null;
        }
        if ($rewrite->getRedirectType()) {
            //Rule requires the request to be redirected to another URL
            //and cannot be processed further.
            return $this->processRedirect($request, $rewrite);
        }
        //Rule provides actual URL that can be processed by a controller.
        $request->setAlias(
            UrlInterface::REWRITE_REQUEST_PATH_ALIAS,
            $rewrite->getRequestPath()
        );
        $request->setPathInfo('/' . $rewrite->getTargetPath());

        return $this->actionFactory->create(
            \Magento\Framework\App\Action\Forward::class
        );
    }

    protected function formatUrlKey($str)
    {
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', $str);
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');

        return $urlKey;
    }

    /**
     * Find rewrite based on request data
     *
     * @param string $requestPath
     * @param int $storeId
     *
     * @return UrlRewrite|null
     */
    protected function getRewrite($requestPath, $storeId)
    {
        return $this->urlFinder->findOneByData(
            [
                UrlRewrite::REQUEST_PATH => ltrim($requestPath, '/'),
                UrlRewrite::STORE_ID => $storeId,
            ]
        );
    }

    /**
     * Process redirect
     *
     * @param RequestInterface $request
     * @param UrlRewrite $rewrite
     *
     * @return ActionInterface|null
     */
    protected function processRedirect($request, $rewrite)
    {
        $target = $rewrite->getTargetPath();
        if ($rewrite->getEntityType() !== Rewrite::ENTITY_TYPE_CUSTOM
            || ($prefix = substr($target, 0, 6)) !== 'http:/' && $prefix !== 'https:'
        ) {
            $target = $this->url->getUrl('', ['_direct' => $target, '_query' => $request->getParams()]);
        }

        return $this->redirect($request, $target, $rewrite->getRedirectType());
    }

    /**
     * Redirect to target URL
     *
     * @param RequestInterface|HttpRequest $request
     * @param string $url
     * @param int $code
     *
     * @return ActionInterface
     */
    protected function redirect($request, $url, $code)
    {
        $this->response->setRedirect($url, $code);
        $request->setDispatched(true);

        return $this->actionFactory->create(Redirect::class);
    }
}
