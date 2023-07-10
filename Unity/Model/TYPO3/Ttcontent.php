<?php

namespace WebVision\Unity\Model\TYPO3;

use WebVision\Unity\Model\ResourceModel\TYPO3\Ttcontent as TtcontentResourceModel;

class Ttcontent extends AbstractModule
{
    protected function _construct()
    {
        $this->_init(TtcontentResourceModel::class);
    }

    public function isColumnPresent($pid, $colPos)
    {
        return $this->_getResource()
            ->isColumnPresent($this, $pid, $colPos);
    }

    public function isElementPresent($uid)
    {
        return $this->_getResource()
            ->isElementPresent($this, $uid);
    }
}
