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
 * @copyright   Copyright (c) 2001-2018 web-vision GmbH (http://www.web-vision.de)
 * @license     <!--LICENSEURL-->
 * @author      Dhaval Kanojiya <dhaval@web-vision.de>
 */
namespace WebVision\Unity\Controller;

class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \WebVision\Unity\Helper\Data
     */
    protected $helper;

    /**
     * @var \WebVision\Unity\Helper\TYPO3
     */
    protected $helperTypo3;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $response;

    /**
     * @var bool
     */
    protected $dispatched;

    /**
     * Router constructor.
     *
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\UrlRewrite\Model\UrlFinderInterface $urlFinder,
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\ResponseInterface $response,
        \WebVision\Unity\Helper\Data $helper,
        \WebVision\Unity\Helper\TYPO3 $helperTypo3,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->helper = $helper;
        $this->logger = $logger;
        $this->helperTypo3 = $helperTypo3;
        $this->actionFactory = $actionFactory;
        $this->response = $response;
    }

    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        if (!$this->dispatched) {
            $identifier = $request->getPathInfo();
            // check if module is enabled
            if ($this->helper->isEnabled()) {
                try {
                    $pageUid = $this->helperTypo3->getPageId();
                } catch (\Exception $e) {
                    $this->logger->critical($e->getMessage());
                }
                var_dump($pageUid);
                //   $pageUid=514;
                if ($pageUid) {
                    $request->setModuleName('webvision_unity');
                    $request->setControllerName('content');
                    $request->setActionName('show');
                    $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $identifier);
                    $request->setDispatched(true);
                    $this->dispatched = true;

                    return $this->actionFactory->create(
                        \Magento\Framework\App\Action\Forward::class,
                        ['request' => $request]
                    );
                }
            } else {
                return false;
            }
        }

        return false;
    }
}
