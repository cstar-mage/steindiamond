<?php 
class Mage_Eternity_Model_Mysql4_Diaprice_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('eternity/diaprice');
    }
}