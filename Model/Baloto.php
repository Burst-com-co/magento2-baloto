<?php

namespace Burst\Baloto\Model;

class Baloto extends \Magento\Framework\Model\AbstractModel
{
    const CACHE_TAG = 'burst_baloto';
    /**
     * Define resource model
     */
    protected function _construct()
    {
        $this->_init('Burst\Baloto\Model\ResourceModel\Baloto');
    }
    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }
}