<?php

namespace WebVision\Unity\Model\Config\Source\Web;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Domain extends ArrayAbstract
{
    const MAGENTO = 0;
    const OWN     = 1;

    public function toArray()
    {
        return [
            static::MAGENTO => __('Same as Magento'),
            static::OWN     => __('Own domain'),
        ];
    }
}
