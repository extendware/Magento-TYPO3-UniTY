<?php

namespace WebVision\Unity\Model\ResourceModel\TYPO3;

use Magento\Framework\DB\Select;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use WebVision\Unity\Model\TYPO3\AbstractModule;

abstract class AbstractModuleResource extends AbstractDb
{
    public function loadByPath(AbstractModule $module, $path)
    {
        $connection = $this->_resources
            ->getConnectionByName('typo3');

        if ($connection) {
            $select = $this->_getLoadSelect('slug', $path, $module);
            $select->reset(Select::COLUMNS)
                ->columns(['uid', 'pid', 'canonical_url', 'doktype'])
                ->where('deleted = ?', 0)
                ->limit(1, 0);
            $data = $connection->fetchRow($select);

            if ($data === FALSE) {
                $select = $this->_getLoadSelect('slug', $path, $module);
                $select->reset(Select::COLUMNS)
                    ->columns(['uid', 'pid', 'canonical_url', 'doktype'])
                    ->where('deleted = ?', 0)
                    ->limit(1, 0);
                $data = $connection->fetchRow($select);
            }

            if ($data) {
                $module->setData($data);
            }
        }

        $this->unserializeFields($module);
        $this->_afterLoad($module);

        return $this;
    }

    public function isPresent(AbstractModule $module, $uid)
    {
        $connection = $this->_resources
            ->getConnectionByName('typo3');

        if ($connection) {
            $select = $this->_getLoadSelect('uid', $uid, $module);
            $select->reset(Select::COLUMNS)
                ->columns('count(uid)')
                ->where('deleted = ?', 0);

            return (bool)$connection->fetchOne($select);
        }

        return false;
    }
}
