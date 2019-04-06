<?php

class Unirgy_Storelocationreport_Model_Mysql4_Storelocationreport extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the storelocationreport_id refers to the key field in your database table.
        $this->_init('storelocationreport/storelocationreport', 'storelocationreport_id');
    }
}