<?php

class Unirgy_Storelocationreport_Model_Mysql4_Storelocationreport_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('storelocationreport/storelocationreport');
    }
}