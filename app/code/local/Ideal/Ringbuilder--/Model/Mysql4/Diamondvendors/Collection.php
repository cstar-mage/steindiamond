<?php

class Ideal_Ringbuilder_Model_Mysql4_Diamondvendors_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ringbuilder/diamondvendors');
    }
}