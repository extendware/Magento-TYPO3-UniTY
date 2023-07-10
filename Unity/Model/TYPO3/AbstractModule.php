<?php

namespace WebVision\Unity\Model\TYPO3;

use Magento\Framework\Model\AbstractModel;

abstract class AbstractModule extends AbstractModel
{
    public function loadByPath($path)
    {
        $this->_getResource()
            ->loadByPath($this, $path);

        return $this;
    }

    public function isPresent($uid)
    {
        return $this->_getResource()
            ->isPresent($this, $uid);
    }
}