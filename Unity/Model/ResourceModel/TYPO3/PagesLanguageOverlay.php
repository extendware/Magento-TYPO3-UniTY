<?php

namespace WebVision\Unity\Model\ResourceModel\TYPO3;

class PagesLanguageOverlay extends AbstractModuleResource
{
    protected function _construct()
    {
        $this->_init('pages_language_overlay', 'uid');
    }
}
