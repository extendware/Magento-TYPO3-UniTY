<?php

namespace WebVision\Unity\Model\Config\Source\Trust;

use WebVision\Unity\Model\Config\Source\ArrayAbstract;

class Order extends ArrayAbstract
{
    const WHITELIST_BLACKLIST = 0;
    const BLACKLIST_WHITELIST = 1;

    /**
     * @inheritDoc
     */
    public function toArray()
    {
        return [
            static::WHITELIST_BLACKLIST => __('Whitelist, Blacklist'),
            static::BLACKLIST_WHITELIST => __('Blacklist, Whitelist'),
        ];
    }
}
