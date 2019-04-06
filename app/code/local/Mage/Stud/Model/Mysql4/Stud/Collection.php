<?php

class Mage_Stud_Model_Mysql4_Stud_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('stud/stud');
    }
}