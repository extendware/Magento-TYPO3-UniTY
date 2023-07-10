<?php
namespace WebVision\Unity\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface as GlobalArrayInterface;

abstract class ArrayAbstract implements GlobalArrayInterface, ArrayInterface
{
    /**
     * @inheritDoc
     */
    public function toOptionArray()
    {
        $options = [];

        foreach ($this->toArray() as $value => $label) {
            $options[] = ['value' => $value, 'label' => $label];
        }

        return $options;
    }
}
