<?php

namespace WebVision\Unity\Block;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\View\Element\Template;
use Magento\Security\Model\AdminSessionsManager;
use Magento\User\Model\User;
use WebVision\Unity\Helper;

class Iframe extends Template
{
    protected $_TYPO3Helper;

    protected $_urlHelper;

    protected $_adminSession;

    protected $_adminUser;

    protected $_directoryList;

    public function __construct(
        Template\Context $context,
        Helper\TYPO3 $TYPO3Helper,
        Helper\URL $urlHelper,
        AdminSessionsManager $adminSessionsManager,
        User $adminUser,
        DirectoryList $directoryList,
        array $data = []
    ) {
        $this->_TYPO3Helper = $TYPO3Helper;
        $this->_urlHelper = $urlHelper;
        $this->_adminSession = $adminSessionsManager;
        $this->_adminUser = $adminUser;
        $this->_directoryList = $directoryList;

        parent::__construct($context, $data);
    }

    // WVTODO add PHPDoc
    public function getIframeUrl($module = 'page')
    {
        if ($module === 'importprofiles') {
            return '/mhsi/config.php';
        }

        $session = $this->_adminSession
            ->getCurrentSession();
        $user = $this->_adminUser
            ->load($session->getUserId());

        $hashFile = $this->_directoryList->getPath('var')
            . DIRECTORY_SEPARATOR . 'hash' . DIRECTORY_SEPARATOR
            . $user->getUserName();
        $hash = '';

        if (file_exists($hashFile)) {
            $hash = file_get_contents($hashFile);
        }

        $url = $this->_urlHelper
            ->setUrl($this->_TYPO3Helper->getT3BaseUrl());
        $url->appendPath('typo3conf/ext/wv_t3unity/Resources/Private/Php/module.php');
        $url->addQueryParam('username', $user->getUserName());
        $url->addQueryParam('token', $hash);
        $url->addQueryParam('module', $module);

        return $this->_urlHelper;
    }
}
