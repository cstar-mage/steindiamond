<?php

class PriceWaiter_NYPWidget_Model_Mysql4_Category_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('nypwidget/category', 'entity_id');
    }
}
