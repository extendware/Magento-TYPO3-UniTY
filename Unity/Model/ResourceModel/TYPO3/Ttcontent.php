<?php
/**
 * web-vision GmbH
 *
 * NOTICE OF LICENSE
 *
 * <!--LICENSETEXT-->
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.web-vision.de for more information.
 *
 * @category    WebVision
 *
 * @copyright   Copyright (c) 2001-2018 web-vision GmbH (http://www.web-vision.de)
 * @license     <!--LICENSEURL-->
 * @author      WebVision <http://www.web-vision.de>
 */
namespace WebVision\Unity\Model\ResourceModel\TYPO3;

use Magento\Framework\DB\Select;

class Ttcontent extends AbstractModuleResource
{
    protected function _construct()
    {
        $this->_init('tt_content', 'uid');
    }

    public function isColumnPresent(\WebVision\Unity\Model\TYPO3\Ttcontent $module, $pid, $colPos)
    {
        $connection = $this->_resources
            ->getConnectionByName('typo3');

        if ($connection) {
            $select = $this->_getLoadSelect('pid', $pid, $module);
            $select->reset(Select::COLUMNS)
                ->columns('count(uid)')
                ->where('colPos = ?', $colPos)
                ->where('deleted = ?', 0)
                ->where('hidden = ?', 0);

            return (bool)$connection->fetchOne($select);
        }

        return false;
    }

    public function isElementPresent(\WebVision\Unity\Model\TYPO3\Ttcontent $module, $uid)
    {
        $connection = $this->_resources
            ->getConnectionByName('typo3');

        if ($connection) {
            $select = $this->_getLoadSelect('uid', $uid, $module);
            $select->reset(Select::COLUMNS)
                ->columns('count(uid)')
                ->where('deleted = ?', 0)
                ->where('hidden = ?', 0);

            return (bool)$connection->fetchOne($select);
        }

        return false;
    }
}
