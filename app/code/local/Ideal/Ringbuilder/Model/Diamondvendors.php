<?php

class Ideal_Ringbuilder_Model_Diamondvendors extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('ringbuilder/diamondvendors');
    }
}