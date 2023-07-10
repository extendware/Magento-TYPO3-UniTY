<?php

namespace WebVision\Unity\Model\Config\Source\Web;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Protocol extends ArrayAbstract
{
    const HTTP    = 0;
    const HTTPS   = 1;
    const CURRENT = 2;

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            self::HTTP    => __('HTTP only'),
            self::HTTPS   => __('HTTPS only'),
            self::CURRENT => __('Currently used'),
        ];
    }
}
