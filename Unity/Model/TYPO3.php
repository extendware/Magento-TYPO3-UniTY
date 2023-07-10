<?php

namespace WebVision\Unity\Model;

use Magento\Framework\DataObject;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\Context;
use Magento\Framework\Phrase;

class TYPO3 extends DataObject
{
    protected $_eventPrefix = 'webvision_unity';

    protected $_eventObject = 'typo3';

    protected $_idFieldName = 'id';

    protected $_hasDataChanges = false;

    protected $_origData;

    protected $_resource;

    protected $_resourceName;

    protected $_eventManager;

    protected $storedData = [];

    public function __construct(
        Context $context,
        ResourceModel\TYPO3 $resource,
        array $data = []
    ) {
        $this->_eventManager = $context->getEventDispatcher();
        $this->_resource = $resource;

        if ($this->_resource instanceof DataObject
            || method_exists($this->_resource, 'getIdFieldName')
        ) {
            $this->_idFieldName = $this->_getResource()
                ->getIdFieldName();
        }

        parent::__construct($data);
        $this->_construct();
    }

    protected function _construct()
    {

    }

    protected function _init($resourceModel)
    {
        $this->_setResourceModel($resourceModel);
        $this->_idFieldName = $this->_getResource()
            ->getIdFieldName();
    }

    public function __sleep()
    {
        $properties = array_keys(get_object_vars($this));
        $properties = array_diff(
            $properties,
            [
                '_eventManager',
                '_resource',
            ]
        );
        return $properties;
    }

    public function __wakeup()
    {
        $objectManager = ObjectManager::getInstance();
        $context = $objectManager->get(Context::class);

        if ($context instanceof Context) {
            $this->_eventManager = $context->getEventDispatcher();
        }
    }

    public function setIdFieldName($name)
    {
        $this->_idFieldName = $name;
        return $this;
    }

    public function getIdFieldName()
    {
        return $this->_idFieldName;
    }

    public function getId()
    {
        return $this->_getData($this->_idFieldName);
    }

    public function setId($value)
    {
        $this->setData($this->_idFieldName, $value);
        return $this;
    }

    public function setData($key, $value = null)
    {
        if ($key === (array)$key) {
            if ($this->_data !== $key) {
                $this->_hasDataChanges = true;
            }
            $this->_data = $key;
        } else {
            if (!array_key_exists($key, $this->_data) || $this->_data[$key] !== $value) {
                $this->_hasDataChanges = true;
            }
            $this->_data[$key] = $value;
        }
        return $this;
    }

    public function unsetData($key = null)
    {
        if ($key === null) {
            $this->setData([]);
        } elseif (is_string($key)) {
            if (isset($this->_data[$key]) || array_key_exists($key, $this->_data)) {
                $this->_hasDataChanges = true;
                unset($this->_data[$key]);
            }
        } elseif ($key === (array)$key) {
            foreach ($key as $element) {
                $this->unsetData($element);
            }
        }
        return $this;
    }

    public function setDataChanges($value)
    {
        $this->_hasDataChanges = (bool)$value;
        return $this;
    }

    public function hasDataChanges()
    {
        return $this->_hasDataChanges;
    }

    public function getOrigData($key = null)
    {
        if ($key === null) {
            return $this->_origData;
        }
        if (isset($this->_origData[$key])) {
            return $this->_origData[$key];
        }
        return null;
    }

    public function setOrigData($key = null, $data = null)
    {
        if ($key === null) {
            $this->_origData = $this->_data;
        } else {
            $this->_origData[$key] = $data;
        }
        return $this;
    }

    public function dataHasChangedFor($field)
    {
        $newData = $this->getData($field);
        $origData = $this->getOrigData($field);

        return $newData !== $origData;
    }

    protected function _setResourceModel($resourceName)
    {
        $this->_resourceName = $resourceName;
    }

    protected function _getResource()
    {
        if (empty($this->_resourceName) && (null === $this->_resource)) {
            throw new LocalizedException(
                new Phrase('The resource isn\'t set.')
            );
        }

        return $this->_resource ?: ObjectManager::getInstance()->get($this->_resourceName);
    }

    public function getResourceName()
    {
        $resourceName = ($this->_resourceName ?: null);

        return $this->_resource ? get_class($this->_resource) : $resourceName;
    }

    public function load($mode = 'page', array $params = [])
    {
        $this->_eventPrefix .= '_' . $mode;
        $this->_eventObject .= '_' . $mode;

        $this->_beforeLoad($mode, $params);
        $this->_getResource()
            ->load($this, $mode, $params);
        $this->_afterLoad();
        $this->setOrigData();
        $this->_hasDataChanges = false;

        return $this;
    }

    protected function _getEventData()
    {
        return [
            'data_object' => $this,
            $this->_eventObject => $this,
        ];
    }

    protected function _beforeLoad($mode, array $params)
    {
        $params = ['object' => $this, 'mode' => $mode, 'params' => $params];
        $this->_eventManager
            ->dispatch('model_load_before', $params);
        $params = array_merge($params, $this->_getEventData());
        $this->_eventManager
            ->dispatch($this->_eventPrefix . '_load_before', $params);

        return $this;
    }

    protected function _afterLoad()
    {
        $this->_eventManager
            ->dispatch('model_load_after', ['object' => $this]);
        $this->_eventManager
            ->dispatch($this->_eventPrefix . '_load_after', $this->_getEventData());

        return $this;
    }

    public function afterLoad()
    {
        $this->getResource()
            ->afterLoad($this);
        $this->_afterLoad();
        $this->updateStoredData();
        return $this;
    }

    public function getResource()
    {
        return $this->_getResource();
    }

    private function updateStoredData()
    {
        if (isset($this->_data)) {
            $this->storedData = $this->_data;
        } else {
            $this->storedData = [];
        }
        return $this;
    }

    public function getStoredData()
    {
        return $this->storedData;
    }


    public function getEventPrefix()
    {
        return $this->_eventPrefix;
    }
}
