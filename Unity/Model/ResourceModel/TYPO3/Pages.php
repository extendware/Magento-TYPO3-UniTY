<?php
namespace WebVision\Unity\Model\ResourceModel\TYPO3;

class Pages extends AbstractModuleResource
{
    protected function _construct()
    {
        $this->_init('pages', 'uid');
    }
}
