<?php

namespace WebVision\Unity\Model\Config\Source\Typo3;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Realurl extends ArrayAbstract
{
    const V1  = 1;
    const V2  = 2;
    const V21 = 3;

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            static::V1  => __('realurl v1.x'),
            static::V2  => __('realurl v2.0'),
            static::V21 => __('realurl v2.1+'),
        ];
    }
}
