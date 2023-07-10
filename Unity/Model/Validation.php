<?php
namespace WebVision\Unity\Model;

use Exception;
use Magento\Framework\Filesystem\DirectoryList;
use WebVision\Unity\Api\ValidationInterface;

class Validation implements ValidationInterface
{
    /**
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $_dir;

    /**
     * Validation constructor.
     *
     * @param \Magento\Framework\Filesystem\DirectoryList $dir
     */
    public function __construct(DirectoryList $dir)
    {
        $this->_dir = $dir;
    }

    /**
     * Validates the username and token.
     *
     * @api
     *
     * @param string $username
     * @param string $token
     *
     * @return string If the cache could be cleared or not.
     */
    public function validateToken($username, $token)
    {
        try {
            $hashDir = $this->_dir->getPath('var') . DIRECTORY_SEPARATOR . 'hash' . DIRECTORY_SEPARATOR;
            $hash = file_get_contents($hashDir . $username);

            return $hash === $token;
        } catch (Exception $e) {
        } catch (\Throwable $t) {
        }

        return false;
    }
}
