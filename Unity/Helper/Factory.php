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
