<?php

class Ideal_Appraisal_Model_Counter extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('IdealAppraisal/Counter');
    }
}