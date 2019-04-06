<?php

class Ideal_Replacesku_Model_Replacesku extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('replacesku/replacesku');
    }
}