<?php
namespace Burst\Baloto\Model\ResourceModel\Baloto;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Burst\Baloto\Model\Baloto',
            'Burst\Baloto\Model\ResourceModel\Baloto'
        );
    }
}