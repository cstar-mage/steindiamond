<?php

class Ideal_Replacesku_Model_Mysql4_Replacesku_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('replacesku/replacesku');
    }
}