<?php

class Dolphin_Specialorder_Model_Mysql4_Specialorder extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {    
        // Note that the customcontact_id refers to the key field in your database table.
        $this->_init('specialorder/specialorder', 'customcontact_id');
    }
}
