<?php

namespace WebVision\Unity\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use WebVision\Unity\Model\TYPO3\Pages;
use WebVision\Unity\Model\TYPO3\PagesLanguageOverlay;

class Factory extends AbstractHelper
{
    protected $_pagesModel;

    protected $_pagesLanguageOverlayModel;

    protected $_dataHelper;

    public function __construct(
        Context $context,
        Data $dataHelper,
        Pages $pages,
        PagesLanguageOverlay $pagesLanguageOverlay
    ) {
        $this->_dataHelper = $dataHelper;
        $this->_pagesModel = $pages;
        $this->_pagesLanguageOverlayModel = $pagesLanguageOverlay;
        parent::__construct($context);
    }

    public function getTypo3PagesModel()
    {
        $model = $this->_pagesModel;

        if ($this->_dataHelper->isMultilanguage()) {
            $model = $this->_pagesLanguageOverlayModel;
        }

        return $model;
    }
}
