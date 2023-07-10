<?php

namespace WebVision\Unity\Model\Config\Source\Error;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Output extends ArrayAbstract
{
    const HTML    = 0;
    const COMMENT = 1;
    const LOG     = 2;

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            static::HTML    => __('As HTML Code'),
            static::COMMENT => __('As HTML Comment'),
            static::LOG     => __('As Log file'),
        ];
    }
}
