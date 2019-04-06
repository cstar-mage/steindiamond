<?php

class Ideal_Ringbuilder_Model_Mysql4_Diamondvendors extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the vendor_id refers to the key field in your database table.
        $this->_init('ringbuilder/diamondvendors', 'vendor_id');
    }
}