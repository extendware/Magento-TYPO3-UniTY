<?php

namespace WebVision\Unity\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\HTTP\Client\Curl;

class CacheObserver implements ObserverInterface
{
    protected $_curl;

    /**
     * CacheObserver constructor.
     *
     * @param \Magento\Framework\HTTP\Client\Curl $curl
     */
    public function __construct(Curl $curl)
    {
        $this->_curl = $curl;
    }

    /**
     * @param Observer $observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        // WVTODO get domain etc. from config
        $url = 'http://typo3domain' . '?eID=wv_t3unity_clearCache&cmd=pages';

        try {
            $this->_curl->get($url);
        } catch (\Exception $e) {

        }
    }
}
