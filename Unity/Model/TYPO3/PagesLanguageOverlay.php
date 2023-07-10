<?php
namespace WebVision\Unity\Model\TYPO3;

use WebVision\Unity\Model\ResourceModel\TYPO3\PagesLanguageOverlay as PagesLanguageOverlayResourceModel;

class PagesLanguageOverlay extends AbstractModule
{
    protected function _construct()
    {
        $this->_init(PagesLanguageOverlayResourceModel::class);
    }
}
