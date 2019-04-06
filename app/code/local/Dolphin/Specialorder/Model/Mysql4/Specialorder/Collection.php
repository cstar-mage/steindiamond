<?php

class Dolphin_Specialorder_Model_Mysql4_Specialorder_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('specialorder/specialorder');
    }
}
