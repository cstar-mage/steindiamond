<?php

class Ideal_Replacesku_Model_Mysql4_Replacesku extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the replacesku_id refers to the key field in your database table.
        $this->_init('replacesku/replacesku', 'replacesku_id');
    }
}