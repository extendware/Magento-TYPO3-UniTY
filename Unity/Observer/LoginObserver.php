<?php
namespace WebVision\Unity\Observer;

use Exception;
use Magento\Backend\Model\Auth\Session;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Filesystem\DirectoryList;

class LoginObserver implements ObserverInterface
{
    protected $_adminSession;

    protected $_dir;

    /**
     * LoginObserver constructor.
     *
     * @param \Magento\Backend\Model\Auth\Session         $session
     * @param \Magento\Framework\Filesystem\DirectoryList $dir
     */
    public function __construct(Session $session, DirectoryList $dir)
    {
        $this->_adminSession = $session;
        $this->_dir = $dir;
    }

    /**
     * @param Observer $observer
     *
     * @throws \Exception
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        $sessionId = $this->_adminSession->getSessionId();
        $username = $this->_adminSession->getUser()->getUserName();
        $hash = hash_hmac('sha256', $sessionId . $username . time(), $sessionId);

        $hashDir = $this->_dir->getPath('var') . DIRECTORY_SEPARATOR . 'hash' . DIRECTORY_SEPARATOR;
        if (!@mkdir($hashDir, 0755, true) && !is_dir($hashDir)) {
            throw new Exception('Could not create folder to store hashes.');
        }

        file_put_contents($hashDir . $username, $hash);
    }
}
